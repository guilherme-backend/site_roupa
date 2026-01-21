@extends('layouts.shop')

@section('title', $category->name)

@section('content')
<div class="bg-gray-50 min-h-screen" x-data="{ mobileFiltersOpen: false }">
    
    <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol role="list" class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117.414 11H16v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5H3.293a1 1 0 01-1.414-1.414l7-7z" clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Home</span>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                            </svg>
                            <a href="{{ route('shop.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Loja</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-indigo-600" aria-current="page">{{ $category->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">{{ $category->name }}</h1>
            <p class="mt-3 text-lg text-gray-500">
                Confira os produtos disponíveis em {{ strtolower($category->name) }}.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between border-b border-gray-200 pb-6 mb-8">
            <div class="flex items-center">
                <button type="button" @click="mobileFiltersOpen = true" class="p-2 -m-2 mr-4 ml-1 text-gray-400 hover:text-gray-500 lg:hidden group flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Filtrar</span>
                </button>
                <p class="hidden sm:block text-sm text-gray-500">
                    {{ $products->total() }} produtos encontrados
                </p>
            </div>

            <form action="{{ url()->current() }}" method="GET">
                <select name="sort" onchange="this.form.submit()" class="cursor-pointer text-sm font-medium text-gray-700 bg-transparent border-none focus:ring-0">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mais recentes</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Menor preço</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Maior preço</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nome (A-Z)</option>
                </select>
            </form>
        </div>

        <div class="lg:grid lg:grid-cols-4 lg:gap-x-8 xl:gap-x-12">
            <aside class="hidden lg:block lg:col-span-1">
                <div class="sticky top-24 space-y-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Categorias</h3>
                        <div class="space-y-3">
                            <a href="{{ route('shop.index') }}" class="flex items-center justify-between text-sm group text-gray-600 hover:text-indigo-600">
                                <span>Ver Todas</span>
                            </a>
                            @foreach($categories as $cat)
                            <a href="{{ route('shop.category', $cat->slug) }}" 
                               class="flex items-center justify-between text-sm group {{ $category->id == $cat->id ? 'text-indigo-600 font-semibold' : 'text-gray-600 hover:text-indigo-600' }}">
                                <span>{{ $cat->name }}</span>
                                <span class="{{ $category->id == $cat->id ? 'bg-indigo-50 text-indigo-600' : 'bg-gray-100 text-gray-600' }} py-0.5 px-2 rounded-full text-xs group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                    {{ $cat->active_products_count }}
                                </span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </aside>

            <div x-show="mobileFiltersOpen" class="relative z-40 lg:hidden" style="display: none;">
                <div class="fixed inset-0 bg-black bg-opacity-25" @click="mobileFiltersOpen = false"></div>
                <div class="fixed inset-0 z-40 flex">
                    <div class="relative ml-auto flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-12 shadow-xl">
                        <div class="flex items-center justify-between px-4">
                            <h2 class="text-lg font-medium text-gray-900">Filtros</h2>
                            <button @click="mobileFiltersOpen = false" class="-mr-2 flex h-10 w-10 items-center justify-center rounded-md bg-white p-2 text-gray-400">
                                <span class="sr-only">Fechar</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <div class="mt-4 px-4 border-t border-gray-200 pt-6">
                            <h3 class="font-medium text-gray-900 mb-4">Categorias</h3>
                            <ul class="space-y-3">
                                <li><a href="{{ route('shop.index') }}" class="text-gray-500">Todas</a></li>
                                @foreach($categories as $cat)
                                <li>
                                    <a href="{{ route('shop.category', $cat->slug) }}" class="{{ $category->id == $cat->id ? 'text-indigo-600 font-medium' : 'text-gray-500' }}">
                                        {{ $cat->name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3">
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-10 gap-x-6">
                        @foreach($products as $product)
                        <div class="group relative flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 overflow-hidden">
                            <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden bg-gray-200 lg:aspect-none lg:h-80">
                                <a href="{{ route('products.show', $product->slug) }}">
                                    @if($product->primaryImage)
                                        <img src="{{ Storage::url($product->primaryImage->image_path) }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center lg:h-full lg:w-full transition-transform duration-500 group-hover:scale-105">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center bg-gray-100 text-gray-400 lg:h-80">
                                            <span class="text-sm">Sem imagem</span>
                                        </div>
                                    @endif
                                </a>
                            </div>
                            <div class="flex flex-col flex-1 p-5">
                                <h3 class="text-lg font-semibold text-gray-900 line-clamp-1 mb-1">
                                    <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                </h3>
                                <div class="mt-auto flex items-end justify-between">
                                    <p class="text-xl font-bold text-indigo-600">R$ {{ number_format($product->base_price, 2, ',', '.') }}</p>
                                    <div class="rounded-full bg-indigo-50 p-2 text-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-12">{{ $products->appends(request()->input())->links() }}</div>
                @else
                    <div class="text-center py-24 bg-white rounded-xl border border-dashed border-gray-300">
                        <h3 class="text-lg font-medium text-gray-900">Esta categoria ainda não tem produtos</h3>
                        <a href="{{ route('shop.index') }}" class="mt-6 inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Ver todos os produtos</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection