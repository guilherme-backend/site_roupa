@extends('layouts.shop')

@section('title', 'Finalizar Compra')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Finalizar Compra</h1>

        <form action="{{ route('checkout.process') }}" method="POST" x-data="checkoutForm()">
            @csrf
            
            <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 lg:items-start">
                <!-- Checkout Form -->
                <div class="space-y-8">
                    <!-- Shipping Information -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Informações de Entrega</h2>
                        
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-6">
                                <label for="shipping_name" class="block text-sm font-medium text-gray-700">Nome Completo</label>
                                <input type="text" name="shipping_name" id="shipping_name" required value="{{ old('shipping_name', Auth::user()->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('shipping_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-3">
                                <label for="shipping_email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="shipping_email" id="shipping_email" required value="{{ old('shipping_email', Auth::user()->email) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('shipping_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-3">
                                <label for="shipping_phone" class="block text-sm font-medium text-gray-700">Telefone</label>
                                <input type="text" name="shipping_phone" id="shipping_phone" required value="{{ old('shipping_phone') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('shipping_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="shipping_zipcode" class="block text-sm font-medium text-gray-700">CEP</label>
                                <input type="text" name="shipping_zipcode" id="shipping_zipcode" required value="{{ old('shipping_zipcode') }}" @blur="calculateShipping" x-model="zipcode" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('shipping_zipcode')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-4">
                                <label for="shipping_address" class="block text-sm font-medium text-gray-700">Endereço</label>
                                <input type="text" name="shipping_address" id="shipping_address" required value="{{ old('shipping_address') }}" x-model="address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('shipping_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="shipping_number" class="block text-sm font-medium text-gray-700">Número</label>
                                <input type="text" name="shipping_number" id="shipping_number" required value="{{ old('shipping_number') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('shipping_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-3">
                                <label for="shipping_complement" class="block text-sm font-medium text-gray-700">Complemento</label>
                                <input type="text" name="shipping_complement" id="shipping_complement" value="{{ old('shipping_complement') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div class="sm:col-span-3">
                                <label for="shipping_neighborhood" class="block text-sm font-medium text-gray-700">Bairro</label>
                                <input type="text" name="shipping_neighborhood" id="shipping_neighborhood" required value="{{ old('shipping_neighborhood') }}" x-model="neighborhood" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('shipping_neighborhood')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-4">
                                <label for="shipping_city" class="block text-sm font-medium text-gray-700">Cidade</label>
                                <input type="text" name="shipping_city" id="shipping_city" required value="{{ old('shipping_city') }}" x-model="city" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('shipping_city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="shipping_state" class="block text-sm font-medium text-gray-700">Estado</label>
                                <input type="text" name="shipping_state" id="shipping_state" required value="{{ old('shipping_state') }}" x-model="state" maxlength="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('shipping_state')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Method -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6" x-show="shippingOptions.length > 0">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Método de Envio</h2>
                        
                        <div class="space-y-4">
                            <template x-for="(option, index) in shippingOptions" :key="index">
                                <label class="relative block cursor-pointer rounded-lg border bg-white px-6 py-4 shadow-sm focus:outline-none sm:flex sm:justify-between">
                                    <input type="radio" name="shipping_method" :value="option.name" @change="selectShipping(option)" required class="sr-only">
                                    <span class="flex items-center">
                                        <span class="flex flex-col text-sm">
                                            <span class="font-medium text-gray-900" x-text="option.name"></span>
                                            <span class="text-gray-500">
                                                <span x-text="option.days"></span> dias úteis
                                            </span>
                                        </span>
                                    </span>
                                    <span class="mt-2 flex text-sm sm:mt-0 sm:flex-col sm:items-end">
                                        <span class="font-medium text-gray-900">R$ <span x-text="option.price.toFixed(2).replace('.', ',')"></span></span>
                                    </span>
                                </label>
                            </template>
                        </div>

                        <input type="hidden" name="shipping_cost" x-model="selectedShipping.price">
                        <input type="hidden" name="shipping_days" x-model="selectedShipping.days">
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Método de Pagamento</h2>
                        
                        <div class="space-y-4">
                            <label class="relative block cursor-pointer rounded-lg border bg-white px-6 py-4 shadow-sm focus:outline-none">
                                <input type="radio" name="payment_method" value="pix" required class="sr-only">
                                <span class="flex items-center">
                                    <span class="flex flex-col text-sm">
                                        <span class="font-medium text-gray-900">PIX</span>
                                        <span class="text-gray-500">Pagamento instantâneo</span>
                                    </span>
                                </span>
                            </label>

                            <label class="relative block cursor-pointer rounded-lg border bg-white px-6 py-4 shadow-sm focus:outline-none">
                                <input type="radio" name="payment_method" value="credit_card" required class="sr-only">
                                <span class="flex items-center">
                                    <span class="flex flex-col text-sm">
                                        <span class="font-medium text-gray-900">Cartão de Crédito</span>
                                        <span class="text-gray-500">Até 12x sem juros</span>
                                    </span>
                                </span>
                            </label>

                            <label class="relative block cursor-pointer rounded-lg border bg-white px-6 py-4 shadow-sm focus:outline-none">
                                <input type="radio" name="payment_method" value="boleto" required class="sr-only">
                                <span class="flex items-center">
                                    <span class="flex flex-col text-sm">
                                        <span class="font-medium text-gray-900">Boleto Bancário</span>
                                        <span class="text-gray-500">Vencimento em 3 dias úteis</span>
                                    </span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="mt-10 lg:mt-0">
                    <div class="bg-gray-50 rounded-lg px-4 py-6 sm:p-6 lg:p-8 sticky top-4">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Resumo do Pedido</h2>

                        <div class="flow-root">
                            <ul role="list" class="-my-6 divide-y divide-gray-200">
                                @foreach($cart as $item)
                                <li class="py-6 flex">
                                    <div class="flex-shrink-0 w-16 h-16 border border-gray-200 rounded-md overflow-hidden">
                                        @if($item['image'])
                                        <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-center object-cover">
                                        @else
                                        <div class="w-full h-full bg-gray-200"></div>
                                        @endif
                                    </div>

                                    <div class="ml-4 flex-1 flex flex-col">
                                        <div>
                                            <div class="flex justify-between text-base font-medium text-gray-900">
                                                <h3 class="text-sm">{{ $item['product_name'] }}</h3>
                                                <p class="ml-4 text-sm">R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}</p>
                                            </div>
                                            <p class="mt-1 text-sm text-gray-500">{{ $item['size'] }} / {{ $item['color'] }}</p>
                                        </div>
                                        <div class="flex-1 flex items-end justify-between text-sm">
                                            <p class="text-gray-500">Qtd: {{ $item['quantity'] }}</p>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <dl class="mt-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-600">Subtotal</dt>
                                <dd class="text-sm font-medium text-gray-900">R$ {{ number_format($subtotal, 2, ',', '.') }}</dd>
                            </div>
                            <div class="flex items-center justify-between" x-show="selectedShipping.price > 0">
                                <dt class="text-sm text-gray-600">Frete</dt>
                                <dd class="text-sm font-medium text-gray-900">R$ <span x-text="selectedShipping.price.toFixed(2).replace('.', ',')"></span></dd>
                            </div>
                            <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                                <dt class="text-base font-medium text-gray-900">Total</dt>
                                <dd class="text-base font-medium text-gray-900">R$ <span x-text="({{ $subtotal }} + selectedShipping.price).toFixed(2).replace('.', ',')"></span></dd>
                            </div>
                        </dl>

                        <div class="mt-6">
                            <button type="submit" class="w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500">
                                Confirmar Pedido
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function checkoutForm() {
        return {
            zipcode: '',
            address: '',
            neighborhood: '',
            city: '',
            state: '',
            shippingOptions: [],
            selectedShipping: { price: 0, days: 0, name: '' },

            async calculateShipping() {
                if (this.zipcode.length >= 8) {
                    try {
                        const response = await fetch('{{ route("checkout.calculate-shipping") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ zipcode: this.zipcode })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.shippingOptions = data.options;
                            // Auto-select first option
                            if (this.shippingOptions.length > 0) {
                                this.selectShipping(this.shippingOptions[0]);
                            }
                        }

                        // Try to get address from ViaCEP
                        const cepResponse = await fetch(`https://viacep.com.br/ws/${this.zipcode.replace(/\D/g, '')}/json/`);
                        const cepData = await cepResponse.json();

                        if (!cepData.erro) {
                            this.address = cepData.logradouro || '';
                            this.neighborhood = cepData.bairro || '';
                            this.city = cepData.localidade || '';
                            this.state = cepData.uf || '';
                        }
                    } catch (error) {
                        console.error('Erro ao calcular frete:', error);
                    }
                }
            },

            selectShipping(option) {
                this.selectedShipping = option;
            }
        }
    }
</script>
@endsection
