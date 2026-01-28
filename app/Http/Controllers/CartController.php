<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $outOfStock = $this->cartService->validateStock();
        
        if (!empty($outOfStock)) {
            session()->flash('warning', 'Alguns itens foram removidos ou atualizados por falta de estoque: ' . implode(', ', $outOfStock));
        }

        $cart = $this->cartService->getCart();
        $total = $this->cartService->getTotal();

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request)
{
    // --- INICIO DO DEBUG ---
    // Isso vai parar o código e mostrar o que está chegando do formulário
    // dd($request->all()); 
    // --- FIM DO DEBUG ---

    $request->validate([
        'variant_id' => 'required|exists:product_variants,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $added = $this->cartService->addItem(
        $request->variant_id,
        $request->quantity
    );

    if (!$added) {
        // Se entrar aqui, é porque o CartService retornou false (provavelmente sem estoque)
        return redirect()->back()->with('error', 'Erro: Estoque insuficiente ou produto indisponível.');
    }

    return redirect()->back()->with('success', 'Produto adicionado ao carrinho!');
}

    public function update(Request $request, $itemKey)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $updated = $this->cartService->updateItem($itemKey, $request->quantity);

        if (!$updated) {
            return redirect()->back()->with('error', 'Não foi possível atualizar a quantidade. Verifique o estoque disponível.');
        }

        return redirect()->back()->with('success', 'Carrinho atualizado com sucesso!');
    }

    public function remove($itemKey)
    {
        $this->cartService->removeItem($itemKey);

        return redirect()->back()->with('success', 'Produto removido do carrinho.');
    }

    public function clear()
    {
        $this->cartService->clear();

        return redirect()->route('cart.index')->with('success', 'Carrinho limpo com sucesso.');
    }
}
