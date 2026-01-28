@extends('layouts.shop')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Minhas Compras</h1>

    @if($orders->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <p class="text-gray-500 text-lg mb-6">Você ainda não realizou nenhuma compra.</p>
            <a href="{{ route('shop.index') }}" class="btn-primary">Começar a comprar</a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition hover:shadow-md">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex flex-wrap justify-between items-center gap-4">
                        <div class="flex gap-8">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-bold">Pedido Realizado</p>
                                <p class="text-sm font-medium text-gray-900">{{ $order->created_at->format('d \d\e F, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-bold">Total</p>
                                <p class="text-sm font-medium text-gray-900">R$ {{ number_format($order->total, 2, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-bold">Status</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider
                                    @if($order->status == 'paid') bg-green-100 text-green-800 
                                    @elseif($order->status == 'pending_payment') bg-yellow-100 text-yellow-800
                                    @elseif($order->status == 'shipped') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $order->status }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 font-bold uppercase">Nº do Pedido</p>
                            <p class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                            <div class="flex-1">
                                @if($order->status == 'shipped' && $order->tracking_code)
                                    <div class="mb-4 p-4 bg-blue-50 rounded-xl border border-blue-100 flex items-center gap-3">
                                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                        <div>
                                            <p class="text-sm font-bold text-blue-900">Seu pedido já foi enviado!</p>
                                            <p class="text-xs text-blue-700">Código de rastreio: <span class="font-bold">{{ $order->tracking_code }}</span></p>
                                        </div>
                                        <a href="https://rastreamento.correios.com.br/app/index.php" target="_blank" class="ml-auto text-xs bg-blue-500 text-white px-3 py-1.5 rounded-lg font-bold hover:bg-blue-600 transition">Rastrear</a>
                                    </div>
                                @endif
                                
                                <div class="space-y-4">
                                    @foreach($order->items as $item)
                                        <div class="flex items-center gap-4">
                                            <div class="w-16 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                                <!-- Aqui entraria a imagem do item -->
                                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-900">{{ $item->product_name }}</p>
                                                <p class="text-xs text-gray-500">Tamanho: {{ $item->variant_size }} | Qtd: {{ $item->quantity }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex flex-col gap-2 w-full md:w-auto">
                                <a href="{{ route('profile.orders.show', $order) }}" class="w-full md:w-48 text-center bg-white border-2 border-gray-100 py-2.5 rounded-xl text-sm font-bold text-gray-700 hover:border-black hover:text-black transition">Ver Detalhes</a>
                                @if($order->status == 'pending_payment')
                                    <a href="#" class="w-full md:w-48 text-center bg-black py-2.5 rounded-xl text-sm font-bold text-white hover:bg-gray-800 transition shadow-lg">Pagar Agora</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
