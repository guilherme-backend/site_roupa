@extends('layouts.shop')

@section('title', 'Teste')

@section('content')
<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Teste de Recuperação</h1>
        
        <p class="mb-4">Se você está vendo isso, o Layout e a Rota estão funcionando!</p>

        @if(isset($products))
            <p>Produtos carregados: {{ $products->count() }}</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                @foreach($products as $product)
                    <div class="border p-4 rounded shadow">
                        <h2 class="font-bold">{{ $product->name }}</h2>
                        <p>R$ {{ $product->base_price }}</p>
                        <a href="{{ route('shop.show', $product->slug) }}" class="text-blue-500">Ver Produto</a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-red-500">A variável $products não chegou na View.</p>
        @endif
    </div>
</div>
@endsection