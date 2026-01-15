@extends('layouts.shop')

@section('title', 'Carrinho de Compras')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Carrinho de Compras</h1>

        @if(count($cart) > 0)
        <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start">
            <!-- Cart Items -->
            <div class="lg:col-span-7">
                <ul role="list" class="border-t border-b border-gray-200 divide-y divide-gray-200">
                    @foreach($cart as $key => $item)
                    <li class="flex py-6 sm:py-10">
                        <div class="flex-shrink-0">
                            @if($item['image'])
                            <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['product_name'] }}" class="w-24 h-24 rounded-md object-center object-cover sm:w-32 sm:h-32">
                            @else
                            <div class="w-24 h-24 sm:w-32 sm:h-32 bg-gray-200 rounded-md flex items-center justify-center">
                                <span class="text-gray-400 text-xs">Sem imagem</span>
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
                                        <p class="text-gray-500">Tamanho: {{ $item['size'] }}</p>
                                        <p class="ml-4 pl-4 border-l border-gray-200 text-gray-500">Cor: {{ $item['color'] }}</p>
                                    </div>
                                    <p class="mt-1 text-sm font-medium text-gray-900">R$ {{ number_format($item['price'], 2, ',', '.') }}</p>
                                </div>

                                <div class="mt-4 sm:mt-0 sm:pr-9">
                                    <form action="{{ route('cart.update', $key) }}" method="POST" class="max-w-xs">
                                        @csrf
                                        @method('PATCH')
                                        <label for="quantity-{{ $key }}" class="sr-only">Quantidade</label>
                                        <select id="quantity-{{ $key }}" name="quantity" onchange="this.form.submit()" class="max-w-full rounded-md border border-gray-300 py-1.5 text-base leading-5 font-medium text-gray-700 text-left shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @for($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" {{ $item['quantity'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </form>

                                    <div class="absolute top-0 right-0">
                                        <form action="{{ route('cart.remove', $key) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="-m-2 p-2 inline-flex text-gray-400 hover:text-gray-500">
                                                <span class="sr-only">Remover</span>
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <p class="mt-4 flex text-sm text-gray-700 space-x-2">
                                <span class="font-medium">Subtotal:</span>
                                <span class="font-bold text-indigo-600">R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}</span>
                            </p>
                        </div>
                    </li>
                    @endforeach
                </ul>

                <div class="mt-6">
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-500">
                            Limpar Carrinho
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="mt-10 lg:mt-0 lg:col-span-5">
                <div class="bg-gray-50 rounded-lg px-4 py-6 sm:p-6 lg:p-8">
                    <h2 class="text-lg font-medium text-gray-900">Resumo do Pedido</h2>

                    <div class="mt-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-600">Subtotal</dt>
                            <dd class="text-sm font-medium text-gray-900">R$ {{ number_format($total, 2, ',', '.') }}</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <dt class="text-base font-medium text-gray-900">Total</dt>
                            <dd class="text-base font-medium text-gray-900">R$ {{ number_format($total, 2, ',', '.') }}</dd>
                        </div>
                    </div>

                    <div class="mt-6">
                        @auth
                        <a href="{{ route('checkout.index') }}" class="w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500 flex items-center justify-center">
                            Finalizar Compra
                        </a>
                        @else
                        <a href="{{ route('login') }}" class="w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500 flex items-center justify-center">
                            Fazer Login para Continuar
                        </a>
                        @endauth
                    </div>

                    <div class="mt-6 text-sm text-center text-gray-500">
                        <p>ou</p>
                        <a href="{{ route('shop.index') }}" class="text-indigo-600 font-medium hover:text-indigo-500 mt-2 inline-block">
                            Continuar Comprando
                            <span aria-hidden="true"> &rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Empty Cart -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Carrinho vazio</h3>
            <p class="mt-1 text-sm text-gray-500">Adicione produtos ao seu carrinho para continuar.</p>
            <div class="mt-6">
                <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z" clip-rule="evenodd" />
                    </svg>
                    Ir para a Loja
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
