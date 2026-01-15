@extends('layouts.shop')

@section('title', 'Meus Pedidos')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Meus Pedidos</h1>
            <p class="mt-2 text-sm text-gray-600">Acompanhe todos os seus pedidos</p>
        </div>

        @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition">
                <!-- Order Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <div>
                                <p class="text-sm text-gray-500">Pedido</p>
                                <p class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Data</p>
                                <p class="text-sm font-medium text-gray-900">{{ $order->created_at->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total</p>
                                <p class="text-sm font-medium text-gray-900">R$ {{ number_format($order->total, 2, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
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
                            <a href="{{ route('customer.orders.show', $order) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                Ver detalhes →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="px-6 py-4">
                    <div class="space-y-4">
                        @foreach($order->items->take(3) as $item)
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded-md"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $item->product_name }}</p>
                                <p class="text-sm text-gray-500">{{ $item->variant_size }} / {{ $item->variant_color }} • Qtd: {{ $item->quantity }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <p class="text-sm font-medium text-gray-900">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach

                        @if($order->items->count() > 3)
                        <p class="text-sm text-gray-500 text-center">
                            + {{ $order->items->count() - 3 }} item(ns) adicional(is)
                        </p>
                        @endif
                    </div>
                </div>

                <!-- Order Footer -->
                @if($order->tracking_code)
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Código de Rastreamento</p>
                            <p class="text-sm font-medium text-gray-900">{{ $order->tracking_code }}</p>
                        </div>
                        <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            Rastrear Pedido
                        </a>
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum pedido encontrado</h3>
            <p class="mt-1 text-sm text-gray-500">Você ainda não fez nenhum pedido.</p>
            <div class="mt-6">
                <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Começar a Comprar
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
