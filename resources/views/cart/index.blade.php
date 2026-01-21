@extends('layouts.shop')

@section('title', 'Carrinho de Compras')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Seu Carrinho</h1>

        {{-- Verifica se a variável $cart existe e tem itens --}}
        @if(isset($cart) && count($cart) > 0)
        <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start xl:gap-x-16">
            
            <section aria-labelledby="cart-heading" class="lg:col-span-7">
                <h2 id="cart-heading" class="sr-only">Itens no seu carrinho</h2>

                <ul role="list" class="border-t border-b border-gray-200 divide-y divide-gray-200">
                    {{-- Loop corrigido para ler o array do CartService --}}
                    @foreach($cart as $key => $item)
                    <li class="flex py-6 sm:py-10 bg-white px-4 sm:px-6 rounded-lg shadow-sm mb-4 border border-gray-100">
                        <div class="flex-shrink-0">
                            @if(!empty($item['image']))
                                <img src="{{ Storage::url($item['image']) }}" 
                                     alt="{{ $item['product_name'] }}" 
                                     class="w-24 h-24 rounded-md object-center object-cover sm:w-32 sm:h-32">
                            @else
                                <div class="w-24 h-24 rounded-md bg-gray-100 flex items-center justify-center sm:w-32 sm:h-32">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="ml-4 flex-1 flex flex-col justify-between sm:ml-6">
                            <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                <div>
                                    <div class="flex justify-between">
                                        <h3 class="text-sm">
                                            <a href="#" class="font-medium text-gray-700 hover:text-gray-800">
                                                {{ $item['product_name'] }}
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="mt-1 flex text-sm">
                                        <p class="text-gray-500">
                                            Tam: {{ $item['size'] }} | Cor: {{ $item['color'] }}
                                        </p>
                                    </div>
                                    <p class="mt-1 text-sm font-medium text-gray-900">
                                        R$ {{ number_format($item['price'], 2, ',', '.') }}
                                    </p>
                                </div>

                                <div class="mt-4 sm:mt-0 sm:pr-9">
                                    <label for="quantity-{{ $key }}" class="sr-only">Quantidade</label>
                                    
                                    <form action="{{ route('cart.update', $key) }}" method="POST" class="flex items-center">
                                        @csrf
                                        @method('PATCH')
                                        <div class="flex items-center border border-gray-300 rounded-md">
                                            <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}" class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-l-md" {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                            </button>
                                            
                                            <span class="w-12 text-center text-sm text-gray-900 py-2">{{ $item['quantity'] }}</span>
                                            
                                            <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-r-md">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            </button>
                                        </div>
                                    </form>

                                    <div class="absolute top-0 right-0">
                                        <form action="{{ route('cart.remove', $key) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="-m-2 p-2 inline-flex text-gray-400 hover:text-red-500 transition-colors">
                                                <span class="sr-only">Remover</span>
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </section>

            <section aria-labelledby="summary-heading" class="mt-16 bg-white rounded-lg shadow-sm border border-gray-100 px-4 py-6 sm:p-6 lg:p-8 lg:mt-0 lg:col-span-5 sticky top-24">
                <h2 id="summary-heading" class="text-lg font-medium text-gray-900">Resumo do Pedido</h2>

                <dl class="mt-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600">Subtotal</dt>
                        <dd class="text-sm font-medium text-gray-900">R$ {{ number_format($total, 2, ',', '.') }}</dd>
                    </div>
                    <div class="border-t border-gray-200 pt-4 flex items-center justify-between">
                        <dt class="flex items-center text-sm text-gray-600">
                            <span>Frete</span>
                        </dt>
                        <dd class="text-sm font-medium text-gray-900">Calculado no checkout</dd>
                    </div>
                    <div class="border-t border-gray-200 pt-4 flex items-center justify-between">
                        <dt class="text-base font-medium text-gray-900">Total</dt>
                        <dd class="text-xl font-bold text-indigo-600">R$ {{ number_format($total, 2, ',', '.') }}</dd>
                    </div>
                </dl>

                <div class="mt-6">
                    <a href="{{ route('checkout.index') }}" class="w-full flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Finalizar Compra
                    </a>
                </div>
                
                <div class="mt-4 text-center">
                    <a href="{{ route('shop.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                        ou Continuar Comprando
                    </a>
                </div>
                
                <div class="mt-6 border-t border-gray-200 pt-4 text-center">
                     <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-red-500 hover:text-red-700 underline">
                            Esvaziar Carrinho
                        </button>
                    </form>
                </div>
            </section>
        </div>
        @else
        <div class="text-center py-24 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="mx-auto h-24 w-24 text-gray-200 mb-6">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h2 class="text-lg font-medium text-gray-900">Seu carrinho está vazio</h2>
            <p class="mt-1 text-sm text-gray-500">Parece que você ainda não adicionou nenhum item.</p>
            <div class="mt-6">
                <a href="{{ route('shop.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                    Começar a Comprar
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection