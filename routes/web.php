<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Rotas Públicas da Loja ---
Route::get('/', [ShopController::class, 'index'])->name('shop.index');

// Rota de Categoria (A QUE ESTAVA FALTANDO)
Route::get('/categoria/{slug}', [ShopController::class, 'category'])->name('shop.category');

// Rota de Detalhes do Produto
Route::get('/produto/{slug}', [ShopController::class, 'show'])->name('shop.show');


// --- Rotas do Carrinho ---
Route::prefix('carrinho')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/adicionar/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/atualizar/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/remover/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/limpar', [CartController::class, 'clear'])->name('cart.clear');
});


// --- Rotas Autenticadas (Checkout e Perfil) ---
Route::middleware(['auth'])->group(function () {

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/processar', [CheckoutController::class, 'process'])->name('checkout.store');
    Route::get('/checkout/sucesso/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Perfil (Padrão Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- Webhook do Mercado Pago ---
Route::post('/webhook/mercadopago', [WebhookController::class, 'handle'])->name('webhook.mercadopago');


// --- Autenticação ---
require __DIR__.'/auth.php';

// Rota de teste isolada
Route::get('/teste-memoria', function () {
    return 'SE VOCÊ ESTÁ LENDO ISSO, O SERVIDOR ESTÁ VIVO!';
});