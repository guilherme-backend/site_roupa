@extends('layouts.shop')

@section('title', 'Pedido #' . $order->order_number)

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('customer.dashboard') }}" class="text-gray-700 hover:text-indigo-600">Minha Conta</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('customer.orders.index') }}" class="ml-1 text-gray-700 hover:text-indigo-600 md:ml-2">Meus Pedidos</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-500 md:ml-2">#{{ $order->order_number }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Order Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Pedido #{{ $order->order_number }}</h1>
                    <p class="mt-2 text-sm text-gray-600">Realizado em {{ $order->created_at->format('d/m/Y \à\s H:i') }}</p>
                </div>
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium 
                    @if($order->status === 'pending_payment') bg-yellow-100 text-yellow-800
                    @elseif($order->status === 'paid') bg-green-100 text-green-800
                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                    @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                    @elseif($order->status === 'delivered') bg-green-100 text-green-800
                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    @switch($order->status)
                        @case('pending_payment') Aguardando Pagamento @break
                        @case('paid') Pago @break
                        @case('processing') Em Processamento @break
                        @case('shipped') Enviado @break
                        @case('delivered') Entregue @break
                        @case('cancelled') Cancelado @break
                        @default {{ $order->status }}
                    @endswitch
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Items -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Itens do Pedido</h2>
                    
                    <ul role="list" class="divide-y divide-gray-200">
                        @foreach($order->items as $item)
                        <li class="py-6 flex">
                            <div class="flex-shrink-0 w-24 h-24 border border-gray-200 rounded-md overflow-hidden bg-gray-200">
                                <!-- Imagem do produto aqui -->
                            </div>

                            <div class="ml-4 flex-1 flex flex-col">
                                <div>
                                    <div class="flex justify-between text-base font-medium text-gray-900">
                                        <h3>{{ $item->product_name }}</h3>
                                        <p class="ml-4">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</p>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">{{ $item->variant_size }} / {{ $item->variant_color }}</p>
                                </div>
                                <div class="flex-1 flex items-end justify-between text-sm">
                                    <p class="text-gray-500">Qtd: {{ $item->quantity }}</p>
                                    <p class="text-gray-900">R$ {{ number_format($item->price, 2, ',', '.') }} cada</p>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>

                    <!-- Order Total -->
                    <dl class="border-t border-gray-200 pt-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-600">Subtotal</dt>
                            <dd class="text-sm font-medium text-gray-900">R$ {{ number_format($order->subtotal, 2, ',', '.') }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-600">Frete ({{ $order->shipping_method }})</dt>
                            <dd class="text-sm font-medium text-gray-900">R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <dt class="text-base font-medium text-gray-900">Total</dt>
                            <dd class="text-base font-medium text-gray-900">R$ {{ number_format($order->total, 2, ',', '.') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Tracking -->
                @if($order->tracking_code)
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Rastreamento</h2>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Código de Rastreamento</p>
                            <p class="mt-1 text-sm font-medium text-gray-900">{{ $order->tracking_code }}</p>
                        </div>
                        <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Rastrear Pedido
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Shipping Address -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Endereço de Entrega</h2>
                    <address class="text-sm not-italic text-gray-700 space-y-1">
                        <p class="font-medium">{{ $order->shipping_name }}</p>
                        <p>{{ $order->shipping_address }}, {{ $order->shipping_number }}</p>
                        @if($order->shipping_complement)
                        <p>{{ $order->shipping_complement }}</p>
                        @endif
                        <p>{{ $order->shipping_neighborhood }}</p>
                        <p>{{ $order->shipping_city }} - {{ $order->shipping_state }}</p>
                        <p>CEP: {{ $order->shipping_zipcode }}</p>
                    </address>
                </div>

                <!-- Payment Info -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Pagamento</h2>
                    <div class="text-sm text-gray-700">
                        <p class="font-medium">
                            @switch($order->payment_method)
                                @case('pix') PIX @break
                                @case('credit_card') Cartão de Crédito @break
                                @case('boleto') Boleto Bancário @break
                                @default {{ $order->payment_method }}
                            @endswitch
                        </p>
                        <p class="mt-2 text-gray-500">
                            Status: 
                            @if($order->status === 'paid' || $order->status === 'processing' || $order->status === 'shipped' || $order->status === 'delivered')
                                <span class="text-green-600 font-medium">Pago</span>
                            @elseif($order->status === 'pending_payment')
                                <span class="text-yellow-600 font-medium">Aguardando</span>
                            @else
                                <span class="text-gray-600 font-medium">{{ $order->status }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                @if($order->status === 'pending_payment')
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <h3 class="text-sm font-medium text-yellow-800 mb-2">Pagamento Pendente</h3>
                    <p class="text-sm text-yellow-700 mb-4">Seu pedido está aguardando confirmação do pagamento.</p>
                    <form action="{{ route('customer.orders.cancel', $order) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200">
                            Cancelar Pedido
                        </button>
                    </form>
                </div>
                @endif

                <!-- Contact Support -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Precisa de Ajuda?</h3>
                    <p class="text-sm text-gray-600 mb-4">Entre em contato com nosso suporte.</p>
                    <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        Falar com Suporte →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
