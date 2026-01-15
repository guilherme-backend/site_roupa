@extends('layouts.shop')

@section('title', 'Início')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">
                    Bem-vindo à Nossa Loja
                </h1>
                <p class="text-xl md:text-2xl mb-8">
                    As melhores roupas com os melhores preços
                </p>
                <a href="{{ route('shop.index') }}" class="inline-block bg-white text-indigo-600 font-semibold px-8 py-3 rounded-lg hover:bg-gray-100 transition">
                    Ver Produtos
                </a>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    @if($categories->count() > 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Categorias</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($categories as $category)
            <a href="{{ route('shop.category', $category->slug) }}" class="group">
                <div class="bg-gray-100 rounded-lg p-6 text-center hover:bg-indigo-50 transition">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600">
                        {{ $category->name }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-2">
                        {{ $category->active_products_count }} produtos
                    </p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Featured Products -->
    @if($featuredProducts->count() > 0)
    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Produtos em Destaque</h2>
                <a href="{{ route('shop.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                    Ver todos →
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition overflow-hidden">
                    <a href="{{ route('products.show', $product->slug) }}">
                        @if($product->primaryImage)
                        <img src="{{ Storage::url($product->primaryImage->image_path) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover">
                        @else
                        <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">Sem imagem</span>
                        </div>
                        @endif
                    </a>
                    <div class="p-4">
                        <p class="text-sm text-gray-500 mb-1">{{ $product->category->name }}</p>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-indigo-600">
                                {{ $product->name }}
                            </a>
                        </h3>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-indigo-600">
                                R$ {{ number_format($product->base_price, 2, ',', '.') }}
                            </span>
                            <a href="{{ route('products.show', $product->slug) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm font-medium">
                                Ver Detalhes
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- New Products -->
    @if($newProducts->count() > 0)
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Novidades</h2>
                <a href="{{ route('shop.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                    Ver todos →
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($newProducts as $product)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition overflow-hidden">
                    <a href="{{ route('products.show', $product->slug) }}">
                        @if($product->primaryImage)
                        <img src="{{ Storage::url($product->primaryImage->image_path) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover">
                        @else
                        <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">Sem imagem</span>
                        </div>
                        @endif
                    </a>
                    <div class="p-4">
                        <p class="text-sm text-gray-500 mb-1">{{ $product->category->name }}</p>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-indigo-600">
                                {{ $product->name }}
                            </a>
                        </h3>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-indigo-600">
                                R$ {{ number_format($product->base_price, 2, ',', '.') }}
                            </span>
                            <a href="{{ route('products.show', $product->slug) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm font-medium">
                                Ver Detalhes
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if($featuredProducts->count() == 0 && $newProducts->count() == 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum produto cadastrado</h3>
        <p class="mt-1 text-sm text-gray-500">Comece cadastrando produtos no painel administrativo.</p>
        @auth
            @if(Auth::user()->email === 'admin@ecommerce.com')
            <div class="mt-6">
                <a href="/admin" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Ir para o Painel Admin
                </a>
            </div>
            @endif
        @endauth
    </div>
    @endif
</div>
@endsection
