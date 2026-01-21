@extends('layouts.shop')

@section('title', $product->name)

@section('content')
<div class="bg-white min-h-screen">
    <nav aria-label="Breadcrumb" class="pt-8 pb-4 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <ol role="list" class="flex items-center space-x-4">
            <li>
                <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117.414 11H16v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5H3.293a1 1 0 01-1.414-1.414l7-7z" clip-rule="evenodd" />
                    </svg>
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
                    <a href="{{ route('shop.category', $product->category->slug) }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                        {{ $product->category->name }}
                    </a>
                </div>
            </li>
        </ol>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 lg:items-start">
            
            @php
                $initialImage = $product->primaryImage ? Storage::url($product->primaryImage->image_path) : null;
            @endphp

            <div class="flex flex-col-reverse" x-data="{ activeImage: '{{ $initialImage }}' }">
                @if($product->images->count() > 1)
                <div class="hidden mt-6 w-full max-w-2xl mx-auto sm:block lg:max-w-none">
                    <div class="grid grid-cols-4 gap-6">
                        @foreach($product->images as $image)
                        <button @click="activeImage = '{{ Storage::url($image->image_path) }}'"
                                type="button"
                                class="relative h-24 bg-white rounded-md flex items-center justify-center text-sm font-medium uppercase text-gray-900 cursor-pointer hover:bg-gray-50 focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-offset-4 overflow-hidden border border-gray-200"
                                :class="{ 'ring-2 ring-indigo-500 ring-offset-2': activeImage === '{{ Storage::url($image->image_path) }}' }">
                            <span class="absolute inset-0 overflow-hidden rounded-md">
                                <img src="{{ Storage::url($image->image_path) }}" alt="" class="w-full h-full object-center object-cover">
                            </span>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="w-full aspect-w-1 aspect-h-1 rounded-2xl bg-gray-100 overflow-hidden border border-gray-100 shadow-sm relative">
                    <template x-if="activeImage">
                        <img :src="activeImage" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-center object-cover sm:rounded-lg transition-opacity duration-300">
                    </template>
                    <template x-if="!activeImage">
                        <div class="w-full h-96 flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                            <svg class="h-24 w-24 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm">Sem imagem disponível</span>
                        </div>
                    </template>
                </div>
            </div>

            <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                @if(session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Sucesso!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Ops!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                        {{ $product->category->name }}
                    </span>
                </div>
                
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">{{ $product->name }}</h1>

                <div class="mt-4">
                    <h2 class="sr-only">Informações do produto</h2>
                    <p class="text-4xl font-bold text-gray-900">
                        R$ {{ number_format($product->base_price, 2, ',', '.') }}
                    </p>
                </div>

                <div class="mt-6">
                    <h3 class="sr-only">Descrição</h3>
                    <div class="text-base text-gray-700 space-y-4 prose prose-indigo max-w-none">
                        {!! $product->description !!}
                    </div>
                </div>

                <form class="mt-8" action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    
                    @if($product->variants && $product->variants->count() > 0)
                        @if($product->variants->count() === 1)
                            <input type="hidden" name="variant_id" value="{{ $product->variants->first()->id }}">
                        @else
                            <div class="mt-6">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-medium text-gray-900">Opções Disponíveis</h3>
                                </div>
                                <div class="mt-4 grid grid-cols-1 gap-4">
                                    <select name="variant_id" class="mt-1 block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md bg-white shadow-sm">
                                        @foreach($product->variants as $variant)
                                            <option value="{{ $variant->id }}" {{ !$variant->isInStock() ? 'disabled' : '' }}>
                                                {{ $variant->name }} 
                                                @if($variant->price_adjustment != 0)
                                                    (R$ {{ number_format($variant->final_price, 2, ',', '.') }})
                                                @endif
                                                @if(!$variant->isInStock())
                                                    - Indisponível
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="mt-8 flex flex-col sm:flex-row gap-4">
                            <div class="w-32">
                                <label for="quantity" class="sr-only">Quantidade</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="number" name="quantity" id="quantity" min="1" value="1" 
                                           class="focus:ring-indigo-500 focus:border-indigo-500 block w-full text-center sm:text-sm border-gray-300 rounded-md py-3 font-semibold"
                                           placeholder="Qtd">
                                </div>
                            </div>

                            <button type="submit" 
                                    class="flex-1 bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-lg shadow-indigo-200">
                                <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                Adicionar ao Carrinho
                            </button>
                        </div>

                    @else
                        <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Este produto está temporariamente indisponível (sem estoque cadastrado).
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </form>

                <section aria-labelledby="policies-heading" class="mt-10 border-t border-gray-200 pt-10">
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2">
                        <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-900">Entrega Rápida</dt>
                                <dd class="mt-1 text-sm text-gray-500">Envio em até 24h úteis.</dd>
                            </div>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-900">Compra Segura</dt>
                                <dd class="mt-1 text-sm text-gray-500">Seus dados protegidos.</dd>
                            </div>
                        </div>
                    </dl>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection