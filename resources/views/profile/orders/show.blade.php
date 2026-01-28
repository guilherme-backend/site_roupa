@extends('layouts.shop')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <a href="{{ route('profile.orders.index') }}" class="text-sm text-gray-500 hover:text-black flex items-center gap-1 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Voltar para minhas compras
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Pedido #{{ $order->order_number }}</h1>
            <p class="text-gray-500">Realizado em {{ $order->created_at->format('d/m/Y \à\s H:i') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 rounded-full text-sm font-bold uppercase tracking-wider
                @if($order->status == 'paid') bg-green-100 text-green-800 
                @elseif($order->status == 'pending_payment') bg-yellow-100 text-yellow-800
                @elseif($order->status == 'shipped') bg-blue-100 text-blue-800
                @elseif($order->status == 'delivered') bg-gray-100 text-gray-800
                @else bg-red-100 text-red-800 @endif">
                {{ $order->status }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Detalhes dos Itens -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900">Itens do Pedido</h2>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <div class="p-6 flex items-center gap-6">
                            <div class="w-20 h-24 bg-gray-50 rounded-xl overflow-hidden flex-shrink-0 flex items-center justify-center border border-gray-100">
                                @if($item->product && $item->product->main_image)
                                    <img src="{{ Storage::url($item->product->main_image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900">{{ $item->product_name }}</h3>
                                <p class="text-sm text-gray-500">Tamanho: {{ $item->variant_size }} | Cor: {{ $item->variant_color ?? 'Padrão' }}</p>
                                <p class="text-sm text-gray-500">Quantidade: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</p>
                                <p class="text-xs text-gray-400">R$ {{ number_format($item->price, 2, ',', '.') }} cada</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="p-6 bg-gray-50 border-t border-gray-100 space-y-3">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span>R$ {{ number_format($order->subtotal, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Frete ({{ $order->shipping_method }})</span>
                        <span>R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-xl font-bold text-gray-900 pt-3 border-t border-gray-200">
                        <span>Total</span>
                        <span>R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            @if($order->status == 'shipped' && $order->tracking_code)
                <div class="bg-blue-600 rounded-3xl p-8 text-white shadow-lg">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-white/20 rounded-2xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">Acompanhe seu Envio</h2>
                            <p class="text-blue-100">Seu pedido está a caminho!</p>
                        </div>
                    </div>
                    <div class="bg-white/10 rounded-2xl p-6 border border-white/10">
                        <p class="text-sm mb-2">Código de Rastreio:</p>
                        <p class="text-2xl font-mono font-bold mb-6">{{ $order->tracking_code }}</p>
                        <a href="{{ $order->tracking_url ?? 'https://rastreamento.correios.com.br/app/index.php' }}" target="_blank" class="inline-block bg-white text-blue-600 px-8 py-3 rounded-xl font-bold hover:bg-gray-100 transition">Rastrear nos Correios</a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Informações de Entrega e Pagamento -->
        <div class="space-y-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Endereço de Entrega
                </h2>
                <div class="text-sm text-gray-600 space-y-1">
                    <p class="font-bold text-gray-900">{{ $order->shipping_name }}</p>
                    <p>{{ $order->shipping_address }}, {{ $order->shipping_number }}</p>
                    @if($order->shipping_complement) <p>{{ $order->shipping_complement }}</p> @endif
                    <p>{{ $order->shipping_neighborhood }}</p>
                    <p>{{ $order->shipping_city }} - {{ $order->shipping_state }}</p>
                    <p>CEP: {{ $order->shipping_zipcode }}</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    Pagamento
                </h2>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Método</span>
                    <span class="text-sm font-bold text-gray-900 uppercase">{{ $order->payment_method }}</span>
                </div>
                @if($order->status == 'pending_payment')
                    <button class="w-full mt-6 bg-black text-white py-3 rounded-xl font-bold hover:bg-gray-800 transition">Pagar Agora</button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
