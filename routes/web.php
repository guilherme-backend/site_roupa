<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\MyOrdersController;
use App\Http\Controllers\ShippingController;

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
    Route::post('/checkout/calcular-frete', [ShippingController::class, 'calculate'])->name('checkout.shipping.calculate');
    Route::get('/checkout/sucesso/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Perfil (Padrão Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Endereços
    Route::get('/profile/addresses', [AddressController::class, 'index'])->name('profile.addresses.index');
    Route::get('/profile/addresses/create', [AddressController::class, 'create'])->name('profile.addresses.create');
    Route::post('/profile/addresses', [AddressController::class, 'store'])->name('profile.addresses.store');
    Route::get('/profile/addresses/{address}/edit', [AddressController::class, 'edit'])->name('profile.addresses.edit');
    Route::put('/profile/addresses/{address}', [AddressController::class, 'update'])->name('profile.addresses.update');
    Route::delete('/profile/addresses/{address}', [AddressController::class, 'destroy'])->name('profile.addresses.destroy');
    Route::post('/profile/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('profile.addresses.default');

    // Minhas Compras
    Route::get('/my-orders', [MyOrdersController::class, 'index'])->name('profile.orders.index');
    Route::get('/my-orders/{order}', [MyOrdersController::class, 'show'])->name('profile.orders.show');
});


// --- Webhook do Mercado Pago ---
Route::post('/webhook/mercadopago', [WebhookController::class, 'handle'])->name('webhook.mercadopago');


// --- Autenticação ---
require __DIR__.'/auth.php';

// Rota de teste isolada
Route::get('/teste-memoria', function () {
    return 'SE VOCÊ ESTÁ LENDO ISSO, O SERVIDOR ESTÁ VIVO!';
});