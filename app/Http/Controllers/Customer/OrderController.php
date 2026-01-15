<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = Auth::user()->orders()
            ->with(['items.product', 'items.productVariant'])
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Verificar se o pedido pertence ao usuário autenticado
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Acesso não autorizado.');
        }

        $order->load(['items.product', 'items.productVariant']);

        return view('customer.orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Acesso não autorizado.');
        }

        if (!$order->canBeCancelled()) {
            return redirect()->back()->with('error', 'Este pedido não pode ser cancelado.');
        }

        $order->markAsCancelled();

        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Pedido cancelado com sucesso.');
    }
}
