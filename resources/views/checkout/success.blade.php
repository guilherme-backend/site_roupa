@extends('layouts.shop')

@section('title', 'Pedido Realizado')

@section('content')
<div class="bg-white">
    <div class="max-w-3xl mx-auto px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
        <div class="max-w-xl">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Pedido Realizado!</h1>
                    <p class="mt-2 text-base text-gray-500">
                        Seu pedido #{{ $order->order_number }} foi confirmado.
                    </p>
                </div>
            </div>

            <div class="mt-10 border-t border-gray-200 pt-10">
                <h2 class="text-lg font-medium text-gray-900">Informações do Pedido</h2>
                
                <dl class="mt-6 text-sm font-medium">
                    <dt class="text-gray-900">Número do Pedido</dt>
                    <dd class="mt-2 text-indigo-600">{{ $order->order_number }}</dd>
                </dl>

                <dl class="mt-6 grid grid-cols-2 gap-x-4 text-sm">
                    <div>
                        <dt class="font-medium text-gray-900">Status</dt>
                        <dd class="mt-2 text-gray-700">
                            @switch($order->status)
                                @case('pending_payment')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Aguardando Pagamento
                                    </span>
                                    @break
                                @case('paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Pago
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $order->status }}
                                    </span>
                            @endswitch
                        </dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-900">Total</dt>
                        <dd class="mt-2 text-gray-700">R$ {{ number_format($order->total, 2, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="mt-10 border-t border-gray-200 pt-10">
                <h2 class="text-lg font-medium text-gray-900">Endereço de Entrega</h2>
                
                <address class="mt-6 text-sm not-italic text-gray-700">
                    <span class="block">{{ $order->shipping_name }}</span>
                    <span class="block">{{ $order->shipping_address }}, {{ $order->shipping_number }}</span>
                    @if($order->shipping_complement)
                    <span class="block">{{ $order->shipping_complement }}</span>
                    @endif
                    <span class="block">{{ $order->shipping_neighborhood }}</span>
                    <span class="block">{{ $order->shipping_city }} - {{ $order->shipping_state }}</span>
                    <span class="block">CEP: {{ $order->shipping_zipcode }}</span>
                </address>
            </div>

            <div class="mt-10 border-t border-gray-200 pt-10">
                <h2 class="text-lg font-medium text-gray-900">Itens do Pedido</h2>

                <ul role="list" class="mt-6 divide-y divide-gray-200">
                    @foreach($order->items as $item)
                    <li class="py-6 flex space-x-6">
                        <div class="flex-auto space-y-1">
                            <h3 class="text-gray-900">
                                <a href="#">{{ $item->product_name }}</a>
                            </h3>
                            <p class="text-sm text-gray-500">{{ $item->variant_size }} / {{ $item->variant_color }}</p>
                            <p class="text-sm text-gray-500">Quantidade: {{ $item->quantity }}</p>
                        </div>
                        <p class="flex-none font-medium text-gray-900">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</p>
                    </li>
                    @endforeach
                </ul>

                <dl class="text-sm font-medium text-gray-900 space-y-6 border-t border-gray-200 pt-6">
                    <div class="flex justify-between">
                        <dt>Subtotal</dt>
                        <dd class="text-gray-700">R$ {{ number_format($order->subtotal, 2, ',', '.') }}</dd>
                    </div>

                    <div class="flex justify-between">
                        <dt>Frete ({{ $order->shipping_method }})</dt>
                        <dd class="text-gray-700">R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</dd>
                    </div>

                    <div class="flex justify-between border-t border-gray-200 pt-6 text-gray-900">
                        <dt class="text-base">Total</dt>
                        <dd class="text-base">R$ {{ number_format($order->total, 2, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="mt-10 border-t border-gray-200 pt-10">
                @if($order->status === 'pending_payment')
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Atenção:</strong> Seu pedido está aguardando confirmação do pagamento. Você será redirecionado para o Mercado Pago para concluir o pagamento.
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="flex space-x-4">
                    <a href="{{ route('customer.orders.show', $order) }}" class="flex-1 bg-indigo-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-center">
                        Ver Detalhes do Pedido
                    </a>
                    <a href="{{ route('shop.index') }}" class="flex-1 bg-white border border-gray-300 rounded-md shadow-sm py-3 px-4 text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-center">
                        Continuar Comprando
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
