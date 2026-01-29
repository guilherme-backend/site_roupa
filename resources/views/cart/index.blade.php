@extends('layouts.shop')

@section('title', 'Meu Carrinho - Moda Urbana')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-center space-x-4 mb-10">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Meu Carrinho</h1>
            <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-bold">{{ isset($cart) ? count($cart) : 0 }} itens</span>
        </div>

        @if(isset($cart) && count($cart) > 0)
        <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start">
            
            <!-- Lista de Itens -->
            <section class="lg:col-span-7">
                <div class="space-y-6">
                    @foreach($cart as $key => $item)
                    <div class="flex py-8 px-6 bg-white rounded-3xl shadow-sm border border-gray-100 group hover:shadow-md transition-shadow">
                        <div class="flex-shrink-0 w-24 h-24 sm:w-40 sm:h-40 rounded-2xl overflow-hidden bg-gray-50 border border-gray-100">
                            @if(!empty($item['image']))
                                <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-center object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>

                        <div class="ml-6 flex-1 flex flex-col">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">
                                        <a href="#" class="hover:text-indigo-600 transition">{{ $item['product_name'] }}</a>
                                    </h3>
                                    <div class="mt-1 flex items-center space-x-2 text-sm text-gray-500">
                                        <span class="bg-gray-100 px-2 py-0.5 rounded-md font-medium">Tam: {{ $item['size'] }}</span>
                                        @if(isset($item['color']))
                                            <span class="bg-gray-100 px-2 py-0.5 rounded-md font-medium">Cor: {{ $item['color'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                <form action="{{ route('cart.remove', $key) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>

                            <div class="mt-auto flex justify-between items-end">
                                <form action="{{ route('cart.update', $key) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center border-2 border-gray-100 rounded-xl p-1">
                                        <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}" class="p-1 text-gray-400 hover:text-indigo-600 disabled:opacity-30" {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                        </button>
                                        <span class="w-10 text-center font-bold text-gray-900">{{ $item['quantity'] }}</span>
                                        <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" class="p-1 text-gray-400 hover:text-indigo-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        </button>
                                    </div>
                                </form>
                                <p class="text-xl font-extrabold text-gray-900">
                                    R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-8 flex justify-between items-center">
                    <a href="{{ route('shop.index') }}" class="inline-flex items-center text-indigo-600 font-bold hover:underline">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Continuar Comprando
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-red-400 hover:text-red-600 font-medium">Esvaziar carrinho</button>
                    </form>
                </div>
            </section>

            <!-- Resumo -->
            <section class="mt-16 lg:mt-0 lg:col-span-5 sticky top-24">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 tracking-tight">Resumo do Pedido</h2>

                    <div class="space-y-4">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-bold text-gray-900">R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Frete</span>
                            @if(isset($shipping) && $shipping['price'] > 0)
                                <span class="font-bold text-gray-900">R$ {{ number_format($shipping['price'], 2, ',', '.') }}</span>
                            @else
                                <span class="text-green-600 font-bold">Grátis</span>
                            @endif
                        </div>
                        <div class="pt-4 border-t border-gray-100 flex justify-between items-end">
                            <span class="text-lg font-bold text-gray-900">Total</span>
                            <div class="text-right">
                                <span class="text-3xl font-extrabold text-indigo-600">R$ {{ number_format($total, 2, ',', '.') }}</span>
                                <p class="text-xs text-gray-400 mt-1">ou 10x de R$ {{ number_format($total/10, 2, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('checkout.index') }}" class="w-full bg-indigo-600 text-white rounded-2xl py-5 flex items-center justify-center text-lg font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all transform hover:-translate-y-0.5">
                            Ir para o Pagamento
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                    
                    <div class="mt-6 flex items-center justify-center space-x-4">
                        <img src="https://logospng.org/download/pix/logo-pix-1024.png" class="h-4 opacity-30 grayscale" alt="Pix">
                        <img src="https://logospng.org/download/visa/logo-visa-4096.png" class="h-4 opacity-30 grayscale" alt="Visa">
                        <img src="https://logospng.org/download/mastercard/logo-mastercard-2048.png" class="h-4 opacity-30 grayscale" alt="Mastercard">
                    </div>
                </div>
            </section>
        </div>
        @else
        <div class="text-center py-32 bg-white rounded-3xl shadow-sm border border-gray-100">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-indigo-50 text-indigo-600 mb-6">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Seu carrinho está vazio</h2>
            <p class="mt-2 text-gray-500 max-w-xs mx-auto">Parece que você ainda não escolheu suas peças favoritas. Vamos às compras?</p>
            <div class="mt-10">
                <a href="{{ route('shop.index') }}" class="bg-indigo-600 text-white px-8 py-4 rounded-2xl font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                    Descobrir Novidades
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
