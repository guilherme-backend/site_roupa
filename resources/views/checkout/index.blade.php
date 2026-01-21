@extends('layouts.shop')

@section('title', 'Finalizar Compra')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="sr-only">Checkout</h1>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf

            <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start">
                
                <div class="lg:col-span-7 space-y-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h2 class="text-lg font-medium text-gray-900">Informações de Contato</h2>
                        </div>
                        <div class="p-6 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                            <div class="sm:col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700">Nome Completo</label>
                                <input type="text" name="name" id="name" value="{{ auth()->user()->name ?? old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm h-10 px-3" required>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ auth()->user()->email ?? old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm h-10 px-3" required>
                            </div>

                            <div class="sm:col-span-1">
                                <label for="document" class="block text-sm font-medium text-gray-700">CPF</label>
                                <input type="text" name="document" id="document" value="{{ old('document') }}" placeholder="000.000.000-00" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm h-10 px-3" required>
                            </div>

                            <div class="sm:col-span-1">
                                <label for="phone" class="block text-sm font-medium text-gray-700">Telefone / Celular</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="(00) 00000-0000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm h-10 px-3" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="hidden lg:block">
                        <button type="submit" class="w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Pagar com Mercado Pago
                        </button>
                    </div>
                </div>

                <div class="mt-10 lg:mt-0 lg:col-span-5">
                    <div class="sticky top-24">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-6 bg-gray-50 border-b border-gray-100">
                                <h2 class="text-lg font-medium text-gray-900">Resumo do Pedido</h2>
                            </div>

                            <ul role="list" class="divide-y divide-gray-200 px-6 max-h-96 overflow-y-auto">
                                @foreach($cart as $item)
                                <li class="py-4 flex">
                                    <div class="flex-shrink-0 h-16 w-16 rounded-md border border-gray-200 overflow-hidden">
                                        @if(!empty($item['image']))
                                            <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['product_name'] }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full bg-gray-100 flex items-center justify-center">
                                                <svg class="h-6 w-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex-1 flex flex-col">
                                        <div>
                                            <div class="flex justify-between text-base font-medium text-gray-900">
                                                <h3 class="line-clamp-1">{{ $item['product_name'] }}</h3>
                                                <p class="ml-4">R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}</p>
                                            </div>
                                            <p class="mt-1 text-sm text-gray-500">Qtd: {{ $item['quantity'] }}</p>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>

                            <dl class="border-t border-gray-200 py-6 px-6 space-y-4">
                                <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                                    <dt class="text-base font-medium text-gray-900">Total</dt>
                                    <dd class="text-base font-medium text-gray-900">R$ {{ number_format($total, 2, ',', '.') }}</dd>
                                </div>
                            </dl>

                            <div class="border-t border-gray-200 p-6">
                                <button type="submit" class="w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 lg:hidden">
                                    Pagar com Mercado Pago
                                </button>
                                <p class="mt-4 text-center text-sm text-gray-500">
                                    Transação segura via Mercado Pago
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection