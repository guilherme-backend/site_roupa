@extends('layouts.shop')

@section('title', 'Loja')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Todos os Produtos</h1>
            <p class="mt-2 text-gray-600">Encontre as melhores roupas para você</p>
        </div>

        <div class="lg:grid lg:grid-cols-4 lg:gap-8">
            <!-- Filters Sidebar -->
            <div class="hidden lg:block">
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtros</h3>
                    
                    <!-- Search -->
                    <form action="{{ route('shop.index') }}" method="GET" class="mb-6">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nome do produto..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <button type="submit" class="mt-2 w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm font-medium">
                            Buscar
                        </button>
                    </form>

                    <!-- Categories -->
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Categorias</h4>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('shop.index') }}" class="text-sm {{ !request('category') ? 'text-indigo-600 font-medium' : 'text-gray-600 hover:text-gray-900' }}">
                                    Todas
                                </a>
                            </li>
                            @foreach($categories as $category)
                            <li>
                                <a href="{{ route('shop.index', ['category' => $category->id]) }}" class="text-sm {{ request('category') == $category->id ? 'text-indigo-600 font-medium' : 'text-gray-600 hover:text-gray-900' }}">
                                    {{ $category->name }} ({{ $category->active_products_count }})
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="lg:col-span-3">
                <!-- Sort and Filter Bar -->
                <div class="flex items-center justify-between mb-6">
                    <p class="text-sm text-gray-600">
                        {{ $products->total() }} produto(s) encontrado(s)
                    </p>
                    <form action="{{ route('shop.index') }}" method="GET" class="flex items-center">
                        @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <label for="sort" class="text-sm text-gray-600 mr-2">Ordenar:</label>
                        <select name="sort" id="sort" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mais recentes</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Menor preço</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Maior preço</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nome A-Z</option>
                        </select>
                    </form>
                </div>

                @if($products->count() > 0)
                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition overflow-hidden border border-gray-200">
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
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                <a href="{{ route('products.show', $product->slug) }}" class="hover:text-indigo-600">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-indigo-600">
                                    R$ {{ number_format($product->base_price, 2, ',', '.') }}
                                </span>
                                <a href="{{ route('products.show', $product->slug) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm font-medium">
                                    Ver
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
                @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum produto encontrado</h3>
                    <p class="mt-1 text-sm text-gray-500">Tente ajustar seus filtros ou busca.</p>
                    <div class="mt-6">
                        <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Ver todos os produtos
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
