@extends('layouts.shop')

@section('title', 'Loja')

@section('content')
<div class="bg-gray-50 min-h-screen" x-data="{ mobileFiltersOpen: false }">
    
    <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Nossa Coleção</h1>
            <p class="mt-3 text-lg text-gray-500 max-w-2xl">
                Explore as últimas tendências e encontre o estilo perfeito para você.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between border-b border-gray-200 pb-6 mb-8">
            <div class="flex items-center">
                <button type="button" 
                        @click="mobileFiltersOpen = true"
                        class="p-2 -m-2 mr-4 ml-1 text-gray-400 hover:text-gray-500 lg:hidden group flex items-center">
                    <span class="sr-only">Filtros</span>
                    <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Filtrar</span>
                </button>
                
                <p class="hidden sm:block text-sm text-gray-500">
                    Mostrando <span class="font-medium text-gray-900">{{ $products->count() }}</span> de <span class="font-medium text-gray-900">{{ $products->total() }}</span> resultados
                </p>
            </div>

            <div class="flex items-center">
                <form action="{{ route('shop.index') }}" method="GET" id="sortForm" class="flex items-center">
                    @foreach(request()->except(['sort', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    
                    <div class="relative inline-block text-left">
                        <label for="sort" class="sr-only">Ordenar por</label>
                        <select name="sort" 
                                id="sort"
                                onchange="this.form.submit()" 
                                class="cursor-pointer group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900 border-none focus:ring-0 focus:border-none bg-transparent py-0 pl-0 pr-8">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mais recentes</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Menor preço</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Maior preço</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nome (A-Z)</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:grid lg:grid-cols-4 lg:gap-x-8 xl:gap-x-12">
            
            <aside class="hidden lg:block lg:col-span-1">
                <div class="sticky top-24 space-y-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Buscar</h3>
                        <form action="{{ route('shop.index') }}" method="GET">
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="O que procura?" 
                                       class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:border-indigo-500 focus:ring-indigo-500 pl-10">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Categorias</h3>
                        <div class="space-y-3">
                            <a href="{{ route('shop.index') }}" 
                               class="flex items-center justify-between text-sm group {{ !request('category') ? 'text-indigo-600 font-semibold' : 'text-gray-600 hover:text-indigo-600' }}">
                                <span>Ver Todas</span>
                            </a>
                            @foreach($categories as $category)
                            <a href="{{ route('shop.index', ['category' => $category->id]) }}" 
                               class="flex items-center justify-between text-sm group {{ request('category') == $category->id ? 'text-indigo-600 font-semibold' : 'text-gray-600 hover:text-indigo-600' }}">
                                <span>{{ $category->name }}</span>
                                <span class="bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                    {{ $category->active_products_count }}
                                </span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </aside>

            <div x-show="mobileFiltersOpen" 
                 class="relative z-40 lg:hidden" 
                 role="dialog" 
                 aria-modal="true"
                 style="display: none;">
                
                <div x-show="mobileFiltersOpen" 
                     x-transition:enter="transition-opacity ease-linear duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity ease-linear duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-black bg-opacity-25" 
                     @click="mobileFiltersOpen = false"></div>

                <div class="fixed inset-0 z-40 flex">
                    <div x-show="mobileFiltersOpen" 
                         x-transition:enter="transition ease-in-out duration-300 transform"
                         x-transition:enter-start="-translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transition ease-in-out duration-300 transform"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="-translate-x-full"
                         class="relative mr-auto flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-12 shadow-xl">
                        
                        <div class="flex items-center justify-between px-4">
                            <h2 class="text-lg font-medium text-gray-900">Filtros</h2>
                            <button type="button" 
                                    @click="mobileFiltersOpen = false"
                                    class="-mr-2 flex h-10 w-10 items-center justify-center rounded-md bg-white p-2 text-gray-400">
                                <span class="sr-only">Fechar menu</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="mt-4 px-4 border-t border-gray-200 pt-6">
                            <form action="{{ route('shop.index') }}" method="GET" class="mb-6">
                                <label class="block text-sm font-medium text-gray-900 mb-2">Buscar</label>
                                <div class="flex gap-2">
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome do produto..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <button type="submit" class="bg-indigo-600 text-white px-3 py-2 rounded-md hover:bg-indigo-700">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </form>

                            <h3 class="text-sm font-bold text-gray-900 mb-4">Categorias</h3>
                            <ul class="space-y-3 font-medium text-gray-900">
                                <li>
                                    <a href="{{ route('shop.index') }}" class="block {{ !request('category') ? 'text-indigo-600' : 'text-gray-500' }}">
                                        Todas as Categorias
                                    </a>
                                </li>
                                @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('shop.index', ['category' => $category->id]) }}" class="block {{ request('category') == $category->id ? 'text-indigo-600' : 'text-gray-500' }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3 mt-6 lg:mt-0">
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-10 gap-x-6">
                        @foreach($products as $product)
                        <div class="group relative flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 overflow-hidden">
                            <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden bg-gray-200 lg:aspect-none lg:h-80 relative">
                                <a href="{{ route('shop.show', $product->slug) }}">
                                    @if($product->primaryImage)
                                        <img src="{{ Storage::url($product->primaryImage->image_path) }}" 
                                             alt="{{ $product->name }}" 
                                             class="h-full w-full object-cover object-center lg:h-full lg:w-full transition-transform duration-500 group-hover:scale-105">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center bg-gray-100 text-gray-400 lg:h-80">
                                            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </a>
                                
                                <div class="absolute bottom-2 left-2">
                                     <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/90 text-gray-800 backdrop-blur-sm shadow-sm">
                                        {{ $product->category->name }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-col flex-1 p-5">
                                <h3 class="text-lg font-semibold text-gray-900 line-clamp-1 mb-1">
                                    <a href="{{ route('shop.show', $product->slug) }}">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                
                                <div class="mt-auto flex items-end justify-between">
                                    <div class="flex flex-col">
                                        <p class="text-sm text-gray-500">Preço</p>
                                        <p class="text-xl font-bold text-indigo-600">
                                            R$ {{ number_format($product->base_price, 2, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="rounded-full bg-indigo-50 p-2 text-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-12">
                        {{ $products->appends(request()->input())->links() }}
                    </div>
                @else
                    <div class="text-center py-24 bg-white rounded-xl border border-dashed border-gray-300">
                        <div class="mx-auto h-24 w-24 text-gray-200 mb-4 bg-gray-50 rounded-full flex items-center justify-center">
                            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">Nenhum produto encontrado</h3>
                        <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">
                            Não encontramos produtos com os filtros selecionados.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('shop.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Limpar Filtros e Ver Tudo
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection