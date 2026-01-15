@extends('layouts.shop')

@section('title', 'Minha Conta')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Minha Conta</h1>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Account Info -->
            <div class="lg:col-span-1">
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Informações da Conta</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Nome</p>
                            <p class="mt-1 text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="mt-1 text-sm font-medium text-gray-900">{{ Auth::user()->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Membro desde</p>
                            <p class="mt-1 text-sm font-medium text-gray-900">{{ Auth::user()->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('profile.edit') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Editar Perfil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Orders Summary -->
            <div class="lg:col-span-2">
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-medium text-gray-900">Meus Pedidos</h2>
                        <a href="{{ route('customer.orders.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            Ver todos →
                        </a>
                    </div>

                    @if($recentOrders->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentOrders as $order)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <h3 class="text-sm font-medium text-gray-900">
                                            Pedido #{{ $order->order_number }}
                                        </h3>
                                        <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
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
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $order->created_at->format('d/m/Y H:i') }} • {{ $order->items->count() }} item(ns)
                                    </p>
                                </div>
                                <div class="ml-4 flex-shrink-0 flex flex-col items-end">
                                    <p class="text-sm font-medium text-gray-900">
                                        R$ {{ number_format($order->total, 2, ',', '.') }}
                                    </p>
                                    <a href="{{ route('customer.orders.show', $order) }}" class="mt-2 text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                        Ver detalhes
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum pedido ainda</h3>
                        <p class="mt-1 text-sm text-gray-500">Comece a comprar agora!</p>
                        <div class="mt-6">
                            <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Ir para a Loja
                            </a>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Quick Stats -->
                <div class="mt-6 grid grid-cols-3 gap-4">
                    <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
                        <p class="mt-1 text-sm text-gray-500">Total de Pedidos</p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ $pendingOrders }}</p>
                        <p class="mt-1 text-sm text-gray-500">Aguardando Pagamento</p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ $completedOrders }}</p>
                        <p class="mt-1 text-sm text-gray-500">Entregues</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
