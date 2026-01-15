<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Services\CartService;
use App\Services\ShippingService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $shippingService;
    protected $paymentService;

    public function __construct(
        CartService $cartService,
        ShippingService $shippingService,
        PaymentService $paymentService
    ) {
        $this->middleware('auth');
        $this->cartService = $cartService;
        $this->shippingService = $shippingService;
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        if ($this->cartService->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho está vazio.');
        }

        $cart = $this->cartService->getCart();
        $subtotal = $this->cartService->getTotal();

        return view('checkout.index', compact('cart', 'subtotal'));
    }

    public function calculateShipping(Request $request)
    {
        $request->validate([
            'zipcode' => 'required|string',
        ]);

        $result = $this->shippingService->calculateShipping($request->zipcode);

        return response()->json($result);
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_zipcode' => 'required|string|max:9',
            'shipping_address' => 'required|string|max:255',
            'shipping_number' => 'required|string|max:20',
            'shipping_complement' => 'nullable|string|max:255',
            'shipping_neighborhood' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'required|string|max:2',
            'shipping_method' => 'required|string',
            'shipping_cost' => 'required|numeric|min:0',
            'shipping_days' => 'required|integer|min:0',
            'payment_method' => 'required|in:pix,credit_card,boleto',
        ]);

        if ($this->cartService->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho está vazio.');
        }

        try {
            DB::beginTransaction();

            $cart = $this->cartService->getCart();
            $subtotal = $this->cartService->getTotal();
            $shippingCost = $request->shipping_cost;
            $total = $subtotal + $shippingCost;

            // Criar pedido
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'status' => 'pending_payment',
                'shipping_name' => $request->shipping_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_number' => $request->shipping_number,
                'shipping_complement' => $request->shipping_complement,
                'shipping_neighborhood' => $request->shipping_neighborhood,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_zipcode' => $request->shipping_zipcode,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'shipping_method' => $request->shipping_method,
                'shipping_days' => $request->shipping_days,
                'payment_method' => $request->payment_method,
            ]);

            // Criar itens do pedido e decrementar estoque
            foreach ($cart as $item) {
                $variant = ProductVariant::find($item['variant_id']);

                if (!$variant || !$variant->decrementStock($item['quantity'])) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Produto indisponível: ' . $item['product_name']);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['variant_id'],
                    'product_name' => $item['product_name'],
                    'variant_size' => $item['size'],
                    'variant_color' => $item['color'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            // Criar preferência de pagamento no Mercado Pago
            $paymentData = [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $request->shipping_name,
                'customer_email' => $request->shipping_email,
                'customer_phone' => $request->shipping_phone,
                'items' => $order->items->map(function ($item) {
                    return [
                        'product_name' => $item->product_name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                    ];
                })->toArray(),
                'shipping_cost' => $shippingCost,
                'shipping_method' => $request->shipping_method,
            ];

            $paymentResult = $this->paymentService->createPreference($paymentData);

            if (!$paymentResult['success']) {
                DB::rollBack();
                return redirect()->back()->with('error', $paymentResult['message']);
            }

            // Salvar ID da preferência no pedido
            $order->update([
                'payment_id' => $paymentResult['preference_id'],
            ]);

            DB::commit();

            // Limpar carrinho
            $this->cartService->clear();

            // Redirecionar para o Mercado Pago
            $initPoint = config('app.env') === 'production' 
                ? $paymentResult['init_point'] 
                : $paymentResult['sandbox_init_point'];

            return redirect($initPoint);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro no checkout: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao processar pedido. Tente novamente.');
        }
    }

    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }

    public function webhook(Request $request)
    {
        try {
            $data = $request->all();
            Log::info('Webhook Mercado Pago recebido', $data);

            $result = $this->paymentService->processWebhook($data);

            if (!$result['success']) {
                return response()->json(['status' => 'error'], 400);
            }

            // Buscar pedido pelo external_reference
            $order = Order::where('order_number', $result['external_reference'])->first();

            if (!$order) {
                Log::warning('Pedido não encontrado: ' . $result['external_reference']);
                return response()->json(['status' => 'not_found'], 404);
            }

            // Atualizar status do pedido baseado no status do pagamento
            switch ($result['status']) {
                case 'approved':
                    $order->markAsPaid();
                    break;
                case 'pending':
                case 'in_process':
                    $order->update(['payment_status' => 'pending']);
                    break;
                case 'rejected':
                case 'cancelled':
                    $order->update([
                        'status' => 'cancelled',
                        'payment_status' => 'rejected',
                    ]);
                    // Devolver estoque
                    foreach ($order->items as $item) {
                        $item->productVariant->incrementStock($item->quantity);
                    }
                    break;
            }

            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
}
