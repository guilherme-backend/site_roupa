@extends('layouts.shop')

@section('title', $product->name . ' - Moda Urbana')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb Moderno -->
        <nav aria-label="Breadcrumb" class="mb-10">
            <ol role="list" class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('shop.index') }}" class="text-gray-500 hover:text-indigo-600 transition">Início</a></li>
                <li class="text-gray-300">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                </li>
                <li><a href="{{ route('shop.category', $product->category->slug) }}" class="text-gray-500 hover:text-indigo-600 transition">{{ $product->category->name }}</a></li>
                <li class="text-gray-300">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                </li>
                <li class="text-indigo-600 font-semibold truncate">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 lg:items-start">
            <!-- Galeria de Imagens -->
            <div class="flex flex-col">
                <div class="w-full aspect-[4/5] rounded-3xl overflow-hidden bg-gray-50 border border-gray-100 shadow-sm group">
                    @if($product->main_image)
                        <img src="{{ Storage::url($product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-center object-cover group-hover:scale-105 transition duration-500">
                    @elseif($product->primaryImage)
                        <img src="{{ Storage::url($product->primaryImage->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-center object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="flex flex-col items-center justify-center h-full text-gray-400">
                            <svg class="w-20 h-20 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="font-medium">Imagem indisponível</span>
                        </div>
                    @endif
                </div>
                
                @if($product->images && $product->images->count() > 1)
                    <div class="mt-6 grid grid-cols-4 gap-4">
                        @foreach($product->images as $image)
                            <button class="relative h-24 rounded-xl overflow-hidden border-2 border-transparent hover:border-indigo-600 focus:outline-none transition-all">
                                <img src="{{ Storage::url($image->image_path) }}" alt="" class="w-full h-full object-center object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Informações do Produto -->
            <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 uppercase tracking-wide">
                            {{ $product->category->name }}
                        </span>
                        <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-gray-900">{{ $product->name }}</h1>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-gray-900">R$ {{ number_format($product->base_price, 2, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">em até 10x sem juros</p>
                        @php $totalStock = $product->total_stock; @endphp
                        @if($totalStock <= 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-2">Esgotado</span>
                        @elseif($totalStock <= 5)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 mt-2">Últimas {{ $totalStock }} unidades!</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-2">Em Estoque</span>
                        @endif
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Descrição</h3>
                    <div class="mt-4 text-base text-gray-600 leading-relaxed prose prose-indigo max-w-none">
                        {!! $product->description !!}
                    </div>
                </div>

                <!-- Calculo de Frete -->
                <div class="mt-10 p-6 bg-indigo-50 rounded-2xl border border-indigo-100">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Calcular Frete</h3>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input type="text" id="cepInput" placeholder="Digite seu CEP (ex: 01001-000)" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" maxlength="9">
                        <button type="button" id="calcularFreteBtn" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition whitespace-nowrap">
                            Calcular
                        </button>
                    </div>
                    
                    <div id="freteResult" class="mt-4 hidden">
                        <div class="space-y-2" id="freteOptions"></div>
                    </div>
                    
                    <div id="freteLoading" class="mt-4 hidden text-center">
                        <div class="inline-flex items-center">
                            <svg class="animate-spin h-5 w-5 text-indigo-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-gray-600">Calculando frete...</span>
                        </div>
                    </div>
                    
                    <div id="freteError" class="mt-4 hidden p-3 bg-red-100 border border-red-300 text-red-700 rounded-lg"></div>
                </div>

                <form class="mt-10" action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf

                    @if($product->variants && $product->variants->count() > 0)
                        <div>
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Tamanho</h3>
                                <a href="#" class="text-xs font-semibold text-indigo-600 hover:underline">Guia de medidas</a>
                            </div>

                            <div class="grid grid-cols-4 gap-3 mt-4">
                                @foreach($product->variants as $variant)
                                    <label class="group relative border-2 rounded-xl py-3 px-3 flex flex-col items-center justify-center text-sm font-bold uppercase cursor-pointer transition-all {{ $variant->stock_quantity == 0 ? 'opacity-30 cursor-not-allowed bg-gray-50 border-gray-100' : 'bg-white border-gray-200 hover:border-indigo-600 text-gray-900' }}">
                                        <input type="radio" name="variant_id" value="{{ $variant->id }}" class="sr-only" {{ $variant->stock_quantity == 0 ? 'disabled' : '' }} required>
                                        <span>{{ $variant->size }}</span>
                                        <span class="text-xs font-normal mt-1 {{ $variant->stock_quantity == 0 ? 'text-red-600' : 'text-gray-500' }}">
                                            {{ $variant->stock_quantity > 0 ? $variant->stock_quantity . ' un.' : 'Sem estoque' }}
                                        </span>
                                        @if($variant->stock_quantity > 0)
                                            <div class="absolute inset-0 border-2 border-transparent rounded-xl peer-checked:border-indigo-600 pointer-events-none"></div>
                                        @endif
                                    </label>
                                @endforeach
                            </div>

                            <!-- Tabela de Estoque por Tamanho -->
                            <div class="mt-8 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-4">Disponibilidade por Tamanho</h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    @foreach($product->variants as $variant)
                                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                                            <div class="text-xs font-bold text-gray-600 uppercase">{{ $variant->size }}</div>
                                            <div class="mt-2 flex items-center justify-between">
                                                <span class="text-sm font-bold {{ $variant->stock_quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $variant->stock_quantity }}
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    @if($variant->stock_quantity == 0)
                                                        Esgotado
                                                    @elseif($variant->stock_quantity <= 3)
                                                        Últimas
                                                    @else
                                                        Disponível
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-indigo-600 h-2 rounded-full transition-all" style="width: {{ min(100, ($variant->stock_quantity / 10) * 100) }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Campo oculto para armazenar frete selecionado -->
                    <input type="hidden" id="selectedShipping" name="selected_shipping" value="">

                    <div class="mt-10 flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <div class="flex items-center border-2 border-gray-200 rounded-xl px-2">
                            <button type="button" class="p-2 text-gray-400 hover:text-gray-600">-</button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" class="w-12 text-center border-none focus:ring-0 font-bold text-gray-900" readonly>
                            <button type="button" class="p-2 text-gray-400 hover:text-gray-600">+</button>
                        </div>
                        
                        <button type="submit" 
                            class="flex-1 bg-gray-900 border border-transparent rounded-xl py-4 px-8 flex items-center justify-center text-base font-bold text-white hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 shadow-lg shadow-gray-200 transition-all transform hover:-translate-y-0.5 disabled:bg-gray-300 disabled:cursor-not-allowed"
                            {{ $product->total_stock <= 0 ? 'disabled' : '' }}>
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            {{ $product->total_stock <= 0 ? 'Produto Esgotado' : 'Adicionar ao Carrinho' }}
                        </button>
                        
                        <button type="button" class="p-4 rounded-xl border-2 border-gray-100 text-gray-400 hover:text-red-500 hover:border-red-100 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </button>
                    </div>
                </form>

                <!-- Benefícios -->
                <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 gap-6 pt-10 border-t border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-50 rounded-full flex items-center justify-center text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-600">Frete grátis para todo o Brasil</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-600">Primeira troca grátis em 30 dias</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produtos Relacionados -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
            <div class="mt-24 pt-16 border-t border-gray-100">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-10 tracking-tight">Quem comprou este, também amou</h2>
                <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($relatedProducts as $related)
                        <div class="group relative">
                            <div class="w-full aspect-[3/4] bg-gray-100 rounded-2xl overflow-hidden group-hover:opacity-90 transition">
                                @if($related->primaryImage)
                                    <img src="{{ Storage::url($related->primaryImage->image_path) }}" alt="{{ $related->name }}" class="w-full h-full object-center object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full text-gray-300">Sem Foto</div>
                                @endif
                            </div>
                            <div class="mt-4 flex justify-between">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-700">
                                        <a href="{{ route('shop.show', $related->slug) }}">
                                            <span aria-hidden="true" class="absolute inset-0"></span>
                                            {{ $related->name }}
                                        </a>
                                    </h3>
                                    <p class="mt-1 text-xs text-gray-500">{{ $related->category->name ?? 'Coleção' }}</p>
                                </div>
                                <p class="text-sm font-bold text-gray-900">R$ {{ number_format($related->base_price, 2, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cepInput = document.getElementById('cepInput');
    const calcularFreteBtn = document.getElementById('calcularFreteBtn');
    const freteResult = document.getElementById('freteResult');
    const freteLoading = document.getElementById('freteLoading');
    const freteError = document.getElementById('freteError');
    const freteOptions = document.getElementById('freteOptions');
    const selectedShipping = document.getElementById('selectedShipping');

    // Formatar CEP enquanto digita
    cepInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 5) {
            value = value.slice(0, 5) + '-' + value.slice(5, 8);
        }
        e.target.value = value;
    });

    // Calcular frete
    calcularFreteBtn.addEventListener('click', async function() {
        const cep = cepInput.value.replace(/\D/g, '');
        
        if (cep.length < 8) {
            freteError.textContent = 'Por favor, digite um CEP válido';
            freteError.classList.remove('hidden');
            freteResult.classList.add('hidden');
            return;
        }

        freteError.classList.add('hidden');
        freteLoading.classList.remove('hidden');
        freteResult.classList.add('hidden');

        try {
            const response = await fetch('{{ route("shipping.calculate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ zipcode: cepInput.value })
            });

            const data = await response.json();
            freteLoading.classList.add('hidden');

            if (data.success && data.options.length > 0) {
                freteOptions.innerHTML = '';
                data.options.forEach((option, index) => {
                    const html = `
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-indigo-600 cursor-pointer transition">
                            <input type="radio" name="shipping_option" value="${option.code}" class="shipping-option" data-price="${option.price}" ${index === 0 ? 'checked' : ''}>
                            <div class="ml-3 flex-1">
                                <div class="font-semibold text-gray-900">${option.name}</div>
                                <div class="text-sm text-gray-500">Entrega em ${option.deadline} dias úteis</div>
                            </div>
                            <div class="font-bold text-indigo-600">R$ ${parseFloat(option.price).toFixed(2).replace('.', ',')}</div>
                        </label>
                    `;
                    freteOptions.innerHTML += html;
                });

                // Adicionar listeners aos radio buttons
                document.querySelectorAll('.shipping-option').forEach(radio => {
                    radio.addEventListener('change', function() {
                        selectedShipping.value = JSON.stringify({
                            code: this.value,
                            price: parseFloat(this.dataset.price)
                        });
                    });
                });

                // Selecionar primeira opção por padrão
                if (document.querySelector('.shipping-option')) {
                    const firstOption = document.querySelector('.shipping-option');
                    firstOption.checked = true;
                    firstOption.dispatchEvent(new Event('change'));
                }

                freteResult.classList.remove('hidden');
            } else {
                freteError.textContent = 'Não foi possível calcular o frete para este CEP';
                freteError.classList.remove('hidden');
            }
        } catch (error) {
            freteLoading.classList.add('hidden');
            freteError.textContent = 'Erro ao calcular frete. Tente novamente.';
            freteError.classList.remove('hidden');
        }
    });

    // Permitir calcular ao pressionar Enter
    cepInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            calcularFreteBtn.click();
        }
    });
});
</script>
@endsection
