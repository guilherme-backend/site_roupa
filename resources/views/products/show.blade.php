@extends('layouts.shop')

@section('title', $product->name)

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-indigo-600">Início</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('shop.index') }}" class="ml-1 text-gray-700 hover:text-indigo-600 md:ml-2">Loja</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('shop.category', $product->category->slug) }}" class="ml-1 text-gray-700 hover:text-indigo-600 md:ml-2">{{ $product->category->name }}</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-500 md:ml-2">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Product Details -->
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
            <!-- Image Gallery -->
            <div class="flex flex-col-reverse">
                <div class="w-full aspect-w-1 aspect-h-1">
                    @if($product->images->count() > 0)
                    <div x-data="{ activeImage: '{{ Storage::url($product->images->first()->image_path) }}' }">
                        <img :src="activeImage" alt="{{ $product->name }}" class="w-full h-96 object-center object-cover sm:rounded-lg">
                        
                        @if($product->images->count() > 1)
                        <div class="mt-4 grid grid-cols-4 gap-4">
                            @foreach($product->images as $image)
                            <button @click="activeImage = '{{ Storage::url($image->image_path) }}'" class="relative h-24 bg-white rounded-md flex items-center justify-center text-sm font-medium uppercase text-gray-900 cursor-pointer hover:bg-gray-50 focus:outline-none focus:ring focus:ring-offset-4 focus:ring-opacity-50">
                                <img src="{{ Storage::url($image->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-center object-cover rounded-md">
                            </button>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="w-full h-96 bg-gray-200 flex items-center justify-center rounded-lg">
                        <span class="text-gray-400 text-lg">Sem imagem</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">{{ $product->name }}</h1>

                <div class="mt-3">
                    <h2 class="sr-only">Informações do produto</h2>
                    <p class="text-3xl text-indigo-600 font-bold">R$ {{ number_format($product->base_price, 2, ',', '.') }}</p>
                </div>

                <div class="mt-6">
                    <h3 class="sr-only">Descrição</h3>
                    <div class="text-base text-gray-700 space-y-6">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                <form action="{{ route('cart.add') }}" method="POST" class="mt-6" x-data="productForm()">
                    @csrf
                    <input type="hidden" name="variant_id" x-model="selectedVariantId">

                    <!-- Size Selection -->
                    @if($sizes->count() > 0)
                    <div>
                        <h3 class="text-sm text-gray-900 font-medium">Tamanho</h3>
                        <div class="mt-2 grid grid-cols-4 gap-2">
                            @foreach($sizes as $size)
                            <button type="button" @click="selectSize('{{ $size }}')" :class="selectedSize === '{{ $size }}' ? 'border-indigo-600 ring-2 ring-indigo-600' : 'border-gray-300'" class="border rounded-md py-3 px-3 flex items-center justify-center text-sm font-medium uppercase hover:bg-gray-50 focus:outline-none">
                                {{ $size }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Color Selection -->
                    @if($colors->count() > 0)
                    <div class="mt-6">
                        <h3 class="text-sm text-gray-900 font-medium">Cor</h3>
                        <div class="mt-2 flex items-center space-x-3">
                            @foreach($colors as $color)
                            <button type="button" @click="selectColor('{{ $color['name'] }}')" :class="selectedColor === '{{ $color['name'] }}' ? 'ring-2 ring-offset-2 ring-indigo-600' : ''" class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none" title="{{ $color['name'] }}">
                                <span class="h-8 w-8 border border-gray-300 rounded-full" style="background-color: {{ $color['hex'] ?? '#cccccc' }}"></span>
                            </button>
                            @endforeach
                        </div>
                        <p class="mt-2 text-sm text-gray-600" x-show="selectedColor" x-text="'Cor selecionada: ' + selectedColor"></p>
                    </div>
                    @endif

                    <!-- Stock Status -->
                    <div class="mt-6">
                        <p class="text-sm" x-show="stockStatus" :class="stockAvailable ? 'text-green-600' : 'text-red-600'" x-text="stockStatus"></p>
                    </div>

                    <!-- Quantity -->
                    <div class="mt-6">
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantidade</label>
                        <input type="number" name="quantity" id="quantity" min="1" :max="maxQuantity" value="1" class="mt-1 block w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <!-- Add to Cart Button -->
                    <div class="mt-10 flex sm:flex-col1">
                        <button type="submit" :disabled="!canAddToCart" :class="canAddToCart ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-400 cursor-not-allowed'" class="max-w-xs flex-1 text-white border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-full">
                            Adicionar ao Carrinho
                        </button>
                    </div>
                </form>

                <script>
                    function productForm() {
                        return {
                            selectedSize: '',
                            selectedColor: '',
                            selectedVariantId: null,
                            stockAvailable: false,
                            stockStatus: '',
                            maxQuantity: 0,
                            canAddToCart: false,
                            variants: @json($product->variants),

                            selectSize(size) {
                                this.selectedSize = size;
                                this.updateVariant();
                            },

                            selectColor(color) {
                                this.selectedColor = color;
                                this.updateVariant();
                            },

                            updateVariant() {
                                if (this.selectedSize && this.selectedColor) {
                                    const variant = this.variants.find(v => v.size === this.selectedSize && v.color === this.selectedColor);
                                    
                                    if (variant) {
                                        this.selectedVariantId = variant.id;
                                        this.maxQuantity = variant.stock_quantity;
                                        this.stockAvailable = variant.stock_quantity > 0;
                                        this.stockStatus = variant.stock_quantity > 0 
                                            ? `${variant.stock_quantity} unidade(s) disponível(is)` 
                                            : 'Produto indisponível';
                                        this.canAddToCart = this.stockAvailable;
                                    } else {
                                        this.resetVariant();
                                    }
                                } else {
                                    this.resetVariant();
                                }
                            },

                            resetVariant() {
                                this.selectedVariantId = null;
                                this.stockAvailable = false;
                                this.stockStatus = 'Selecione tamanho e cor';
                                this.maxQuantity = 0;
                                this.canAddToCart = false;
                            }
                        }
                    }
                </script>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Produtos Relacionados</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition overflow-hidden border border-gray-200">
                    <a href="{{ route('products.show', $relatedProduct->slug) }}">
                        @if($relatedProduct->primaryImage)
                        <img src="{{ Storage::url($relatedProduct->primaryImage->image_path) }}" alt="{{ $relatedProduct->name }}" class="w-full h-64 object-cover">
                        @else
                        <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">Sem imagem</span>
                        </div>
                        @endif
                    </a>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                            <a href="{{ route('products.show', $relatedProduct->slug) }}" class="hover:text-indigo-600">
                                {{ $relatedProduct->name }}
                            </a>
                        </h3>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-indigo-600">
                                R$ {{ number_format($relatedProduct->base_price, 2, ',', '.') }}
                            </span>
                            <a href="{{ route('products.show', $relatedProduct->slug) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm font-medium">
                                Ver
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
