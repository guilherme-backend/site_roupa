@extends('layouts.shop')

@section('title', 'Moda Urbana - As melhores tendências')

@section('content')
<!-- Hero Section -->
<div class="relative bg-indigo-900 overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-indigo-900 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">Estilo que define</span>
                        <span class="block text-indigo-400 xl:inline">quem você é</span>
                    </h1>
                    <p class="mt-3 text-base text-indigo-100 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        Descubra a nova coleção de verão. Peças exclusivas, confortáveis e com o design que você procura para renovar seu guarda-roupa.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-md shadow">
                            <a href="#categorias" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                                Ver Coleção
                            </a>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 md:py-4 md:text-lg md:px-10">
                                Ofertas
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-indigo-800 flex items-center justify-center">
        <!-- Placeholder para uma imagem bonita -->
        <div class="text-indigo-300 text-9xl font-bold opacity-20 transform rotate-12">MODA</div>
    </div>
</div>

<!-- Categorias Rápidas (Dinâmicas) -->
@if(isset($categories) && $categories->count() > 0)
<div id="categorias" class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-extrabold text-gray-900 mb-8 tracking-tight">Navegue por Categoria</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($categories as $cat)
                <a href="{{ route('shop.category', $cat->slug) }}" class="group relative rounded-xl overflow-hidden aspect-square bg-gradient-to-br from-indigo-50 to-indigo-100 hover:shadow-lg transition-all duration-300">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <div class="font-bold text-gray-800 group-hover:scale-110 transition duration-300 text-lg">{{ $cat->name }}</div>
                            <div class="text-xs text-gray-600 mt-1">Explorar</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Lista de Produtos -->
<div id="produtos" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-10">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    @if(isset($category))
                        {{ $category->name }}
                    @else
                        Destaques da Semana
                    @endif
                </h2>
                <p class="mt-2 text-gray-500">
                    @if(isset($category))
                        Confira todos os produtos da categoria {{ $category->name }}.
                    @else
                        Confira o que há de mais novo em nossa loja.
                    @endif
                </p>
            </div>
            @if(!isset($category))
                <a href="#" class="text-indigo-600 font-semibold hover:text-indigo-500 transition">Ver tudo &rarr;</a>
            @endif
        </div>

        @if(isset($products) && $products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10">
                @foreach($products as $product)
                    <div class="group relative flex flex-col">
                        <div class="relative w-full aspect-[3/4] rounded-2xl overflow-hidden bg-gray-200 group-hover:opacity-90 transition shadow-sm">
                            @if($product->main_image)
                                <img src="{{ Storage::url($product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-center object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            
                            <!-- Indicador de Estoque -->
                            @php $totalStock = $product->total_stock; @endphp
                            @if($totalStock > 0)
                                <div class="absolute top-4 left-4">
                                    @if($totalStock <= 5)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-800">
                                            Últimas {{ $totalStock }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                            Em Estoque
                                        </span>
                                    @endif
                                </div>
                            @else
                                <div class="absolute top-4 left-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                        Esgotado
                                    </span>
                                </div>
                            @endif
                            
                            <div class="absolute bottom-4 right-4 translate-y-12 group-hover:translate-y-0 transition-transform duration-300">
                                <button class="bg-white p-3 rounded-full shadow-xl text-indigo-600 hover:bg-indigo-600 hover:text-white transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-gray-700">
                                    <a href="{{ route('shop.show', $product->slug) }}">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <p class="mt-1 text-xs text-gray-500">{{ $product->category->name ?? 'Coleção' }}</p>
                            </div>
                            <p class="text-sm font-bold text-gray-900">R$ {{ number_format($product->base_price, 2, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Paginação -->
            @if($products->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Nenhum produto encontrado</h3>
                <p class="mt-2 text-gray-500">Estamos preparando as melhores peças para você. Volte em breve!</p>
                <div class="mt-6">
                    <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                        Voltar à Loja
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Newsletter -->
<div class="bg-white border-y border-gray-100 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-gray-900">Fique por dentro das novidades</h2>
        <p class="mt-4 text-lg text-gray-500">Assine nossa newsletter e receba 10% de desconto na sua primeira compra.</p>
        <form class="mt-8 sm:flex justify-center max-w-md mx-auto">
            <input type="email" required class="w-full px-5 py-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs" placeholder="Seu melhor e-mail">
            <button type="submit" class="mt-3 sm:mt-0 sm:ml-3 w-full sm:w-auto px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition">
                Assinar
            </button>
        </form>
    </div>
</div>
@endsection
