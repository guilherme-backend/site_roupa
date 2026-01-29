@extends('layouts.shop')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-10 text-center lg:text-left">Finalizar Compra</h1>

	    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
	        @csrf
            <!-- Campo oculto para o frete da sessão -->
            <input type="hidden" name="shipping_price" id="shipping_price" value="{{ $shipping['price'] ?? 0 }}">
	        <div class="lg:grid lg:grid-cols-12 lg:gap-x-12">
            
            <!-- Coluna da Esquerda: Endereço e Dados -->
            <div class="lg:col-span-7 space-y-8">
                
                <!-- Seção de Endereço -->
                <section class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Endereço de Entrega
                        </h2>
                        <a href="{{ route('profile.addresses.create') }}" class="text-sm text-blue-600 font-bold hover:underline">Novo endereço</a>
                    </div>

                    @php $addresses = Auth::user()->addresses; @endphp

                    @if($addresses->isEmpty())
                        <div class="text-center py-6 border-2 border-dashed border-gray-100 rounded-2xl">
                            <p class="text-gray-500 mb-4">Você não tem endereços salvos.</p>
                            <a href="{{ route('profile.addresses.create') }}" class="btn-primary">Cadastrar Endereço</a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($addresses as $address)
                                <label class="relative flex p-4 border rounded-2xl cursor-pointer hover:border-black transition {{ $address->is_default ? 'border-black bg-gray-50' : 'border-gray-100' }}">
                                    <input type="radio" name="address_id" value="{{ $address->id }}" class="mt-1 text-black focus:ring-black" {{ $address->is_default ? 'checked' : '' }} onchange="calculateShipping('{{ $address->zipcode }}')">
                                    <div class="ml-4">
                                        <p class="font-bold text-gray-900">{{ $address->recipient_name }} ({{ $address->label ?? 'Casa' }})</p>
                                        <p class="text-sm text-gray-600">{{ $address->full_address }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </section>

                <!-- Seção de Frete -->
                <section id="shipping-section" class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 {{ $addresses->isEmpty() ? 'opacity-50 pointer-events-none' : '' }}">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Opções de Envio
                    </h2>
                    <div id="shipping-options" class="space-y-4">
                        <p class="text-gray-500 italic">Selecione um endereço para calcular o frete...</p>
                    </div>
                </section>

                <!-- Dados Pessoais -->
                <section class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Dados para o Faturamento
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nome Completo</label>
                            <input type="text" name="name" value="{{ Auth::user()->name }}" required class="w-full rounded-xl border-gray-200 focus:border-black focus:ring-black">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">CPF</label>
                            <input type="text" name="document" required placeholder="000.000.000-00" class="w-full rounded-xl border-gray-200 focus:border-black focus:ring-black">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">E-mail</label>
                            <input type="email" name="email" value="{{ Auth::user()->email }}" required class="w-full rounded-xl border-gray-200 focus:border-black focus:ring-black">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Telefone</label>
                            <input type="text" name="phone" required placeholder="(00) 00000-0000" class="w-full rounded-xl border-gray-200 focus:border-black focus:ring-black">
                        </div>
                    </div>
                </section>
            </div>

            <!-- Coluna da Direita: Resumo do Pedido -->
            <div class="lg:col-span-5 mt-12 lg:mt-0">
                <div class="bg-gray-900 rounded-3xl p-8 sticky top-24 text-white shadow-2xl">
                    <h2 class="text-2xl font-bold mb-8">Resumo do Pedido</h2>
                    
                    <div class="space-y-6 mb-8 max-h-64 overflow-y-auto pr-2">
                        @foreach($cart as $item)
                            <div class="flex justify-between items-center gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-white/10 rounded-lg flex-shrink-0 flex items-center justify-center">
                                        @if(!empty($item['image']))
                                            <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['product_name'] }}" class="h-full w-full object-cover rounded-lg">
                                        @else
                                            <svg class="w-6 h-6 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold line-clamp-1">{{ $item['product_name'] }}</p>
                                        <p class="text-xs text-white/50">{{ $item['quantity'] }}x R$ {{ number_format($item['price'], 2, ',', '.') }}</p>
                                    </div>
                                </div>
                                <p class="text-sm font-bold">R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>

	                    <div class="space-y-4 border-t border-white/10 pt-6">
	                        <div class="flex justify-between text-white/70">
	                            <span>Subtotal</span>
	                            <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
	                        </div>
	                        <div class="flex justify-between text-white/70">
	                            <span>Frete</span>
	                            <span id="display-shipping">R$ {{ number_format($shipping['price'] ?? 0, 2, ',', '.') }}</span>
	                        </div>
	                        <div class="flex justify-between text-xl font-bold pt-4 border-t border-white/20">
	                            <span>Total</span>
	                            <span id="display-total" data-base="{{ $subtotal }}">R$ {{ number_format($total, 2, ',', '.') }}</span>
	                        </div>
	                    </div>

                    <button type="submit" id="submit-btn" class="w-full mt-8 bg-white text-black py-5 rounded-2xl font-bold text-lg hover:bg-gray-100 transition shadow-xl flex items-center justify-center gap-2">
                        Pagar com Mercado Pago
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                    <p class="text-center text-xs text-white/40 mt-4 italic">Pagamento 100% seguro e criptografado</p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function calculateShipping(zipcode) {
        const optionsDiv = document.getElementById('shipping-options');
        optionsDiv.innerHTML = '<div class="flex items-center gap-2 text-gray-500"><svg class="animate-spin h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Calculando frete...</div>';

        fetch("{{ route('checkout.shipping.calculate') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ zipcode: zipcode })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                let html = '';
                data.options.forEach((opt, index) => {
                    html += `
                        <label class="flex items-center justify-between p-4 border border-gray-100 rounded-2xl cursor-pointer hover:border-black transition">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="shipping_method" value="${opt.code}" data-price="${opt.price}" data-name="${opt.name}" class="text-black focus:ring-black" ${index === 0 ? 'checked' : ''} onchange="updateTotal(this)">
                                <div>
                                    <p class="font-bold text-gray-900">${opt.name}</p>
                                    <p class="text-xs text-gray-500">Entrega em até ${opt.deadline} dias úteis</p>
                                </div>
                            </div>
                            <p class="font-bold text-gray-900">R$ ${opt.price.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</p>
                        </label>
                    `;
                });
                optionsDiv.innerHTML = html;
                
                // Atualiza o total com a primeira opção selecionada por padrão
                const firstRadio = optionsDiv.querySelector('input[type="radio"]');
                if(firstRadio) updateTotal(firstRadio);
            }
        });
    }

	    function updateTotal(radio) {
	        const price = parseFloat(radio.dataset.price);
            document.getElementById('shipping_price').value = price;
	        const baseTotal = parseFloat(document.getElementById('display-total').dataset.base);
	        const finalTotal = baseTotal + price;
	
	        document.getElementById('display-shipping').innerText = `R$ ${price.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
	        document.getElementById('display-total').innerText = `R$ ${finalTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
	    }

    // Calcula frete inicial se houver endereço padrão
    window.onload = () => {
        const checkedAddress = document.querySelector('input[name="address_id"]:checked');
        if(checkedAddress) {
            const defaultZip = "{{ Auth::user()->defaultAddress->zipcode ?? '' }}";
            if(defaultZip) calculateShipping(defaultZip);
        }
    };
</script>
@endsection
