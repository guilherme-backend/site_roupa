<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop & Products
Route::get('/loja', [ShopController::class, 'index'])->name('shop.index');
Route::get('/categoria/{slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/produto/{slug}', [ProductController::class, 'show'])->name('products.show');

// Cart
Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrinho/adicionar', [CartController::class, 'add'])->name('cart.add');
Route::patch('/carrinho/atualizar/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrinho/remover/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/carrinho/limpar', [CartController::class, 'clear'])->name('cart.clear');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Customer Dashboard
    Route::get('/minha-conta', [DashboardController::class, 'index'])->name('customer.dashboard');
    
    // Customer Orders
    Route::get('/meus-pedidos', [OrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/meus-pedidos/{order}', [OrderController::class, 'show'])->name('customer.orders.show');
    Route::post('/meus-pedidos/{order}/cancelar', [OrderController::class, 'cancel'])->name('customer.orders.cancel');
    
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/calcular-frete', [CheckoutController::class, 'calculateShipping'])->name('checkout.calculate-shipping');
    Route::post('/checkout/processar', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/sucesso/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
});

// Webhook do Mercado Pago (não requer autenticação)
Route::post('/webhook/mercadopago', [CheckoutController::class, 'webhook'])->name('webhook.mercadopago');

require __DIR__.'/auth.php';
