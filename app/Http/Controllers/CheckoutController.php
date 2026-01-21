<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\PaymentService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index(CartService $cartService)
    {
        $cart = $cartService->getCart();
        $total = $cartService->getTotal();

        if (empty($cart)) {
            return redirect()->route('shop.index');
        }

        return view('checkout.index', compact('cart', 'total'));
    }

    public function process(Request $request, CartService $cartService, PaymentService $paymentService)
    {
        // 1. Validação
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'document' => 'required|string', 
            'phone' => 'required|string',
        ]);

        $cart = $cartService->getCart();
        $total = $cartService->getTotal();

        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Seu carrinho está vazio.');
        }

        try {
            DB::beginTransaction();

            // 2. Criar o Pedido
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'status' => 'pending_payment',
                'total' => $total,
                'subtotal' => $total,
                'shipping_cost' => 0,
                'shipping_name' => $validated['name'],
                'shipping_email' => $validated['email'],
                'shipping_phone' => $validated['phone'],
                'shipping_address' => 'Endereço Digital / Não informado',
                'shipping_number' => 'S/N',
                'shipping_neighborhood' => 'Digital',
                'shipping_city' => 'Digital',
                'shipping_state' => 'XX',
                'shipping_zipcode' => '00000-000',
                'payment_method' => 'mercadopago',
                'shipping_method' => 'digital', 
            ]);

            // 3. Salvar os Itens do Pedido
            foreach ($cart as $item) {
                $variantId = $item['variant_id'] ?? $item['product_variant_id'] ?? $item['id'] ?? null;
                $sizeName = 'Único'; 
                $colorName = null;

                if ($variantId) {
                    $variant = ProductVariant::find($variantId);
                    if ($variant) {
                        $sizeName = $variant->size ?? $variant->name ?? 'Único';
                        $colorName = $variant->color ?? null;
                    }
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $variantId,
                    'product_name' => $item['product_name'],
                    'variant_size' => $sizeName,
                    'variant_color' => $colorName,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            // 4. Preparar dados para o MP
            $paymentData = [
                'items' => $cart,
                'shipping_cost' => 0,
                'customer_name' => $validated['name'],
                'customer_email' => $validated['email'],
                'customer_document' => $validated['document'], 
                'customer_phone' => $validated['phone'], // Enviando telefone corretamente agora
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ];

            // 5. Gerar preferência
            $preference = $paymentService->createPreference($paymentData);

            DB::commit();

            // VERIFICAÇÃO FINAL (Sem dd)
            if (isset($preference['id'])) {
                $cartService->clear();
                
                // Define o link correto (Sandbox ou Produção)
                $link = config('app.env') === 'local' 
                        ? ($preference['sandbox_init_point'] ?? $preference['init_point'])
                        : $preference['init_point'];
                
                return redirect()->away($link);
            } else {
                // Se der erro, mostra qual foi
                Log::error('Erro MP:', ['response' => $preference]);
                return back()->with('error', $preference['message'] ?? 'Erro ao comunicar com Mercado Pago.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro Checkout:', ['msg' => $e->getMessage()]);
            return back()->with('error', 'Erro ao processar: ' . $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        return view('checkout.success', compact('order'));
    }
}