@extends('layouts.shop')

@section('content')
<div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <a href="{{ route('profile.addresses.index') }}" class="text-sm text-gray-500 hover:text-black flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Voltar para meus endereços
        </a>
        <h1 class="text-3xl font-bold text-gray-900 mt-4">Editar Endereço</h1>
    </div>

    <form action="{{ route('profile.addresses.update', $address) }}" method="POST" class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-2">Nome do Destinatário</label>
                <input type="text" name="recipient_name" value="{{ $address->recipient_name }}" required class="w-full rounded-xl border-gray-200 focus:border-black focus:ring-black">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">CEP</label>
                <input type="text" name="zipcode" id="zipcode" value="{{ $address->zipcode }}" required maxlength="9" class="w-full rounded-xl border-gray-200 focus:border-black focus:ring-black">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Apelido (Opcional)</label>
                <input type="text" name="label" value="{{ $address->label }}" class="w-full rounded-xl border-gray-200 focus:border-black focus:ring-black">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-2">Endereço</label>
                <input type="text" name="address" id="address" value="{{ $address->address }}" required class="w-full rounded-xl border-gray-200 focus:border-black focus:ring-black">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Número</label>
                <input type="text" name="number" value="{{ $address->number }}" required class="w-full rounded-xl border-gray-200 focus:border-black focus:ring-black">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Complemento</label>
                <input type="text" name="complement" value="{{ $address->complement }}" class="w-full rounded-xl border-gray-200 focus:border-black focus:ring-black">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-2">Bairro</label>
                <input type="text" name="neighborhood" id="neighborhood" value="{{ $address->neighborhood }}" required class="w-full rounded-xl border-gray-200 focus:border-black focus:ring-black">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Cidade</label>
                <input type="text" name="city" id="city" value="{{ $address->city }}" required class="w-full rounded-xl border-gray-200 focus:border-black focus:ring-black">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Estado (UF)</label>
                <input type="text" name="state" id="state" value="{{ $address->state }}" required maxlength="2" class="w-full rounded-xl border-gray-200 focus:border-black focus:ring-black uppercase">
            </div>

            <div class="md:col-span-2">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_default" value="1" {{ $address->is_default ? 'checked' : '' }} class="rounded text-black focus:ring-black">
                    <span class="text-sm text-gray-700">Definir como endereço padrão</span>
                </label>
            </div>
        </div>

        <button type="submit" class="w-full mt-8 bg-black text-white py-4 rounded-xl font-bold hover:bg-gray-800 transition shadow-lg">
            Atualizar Endereço
        </button>
    </form>
</div>

<script>
    document.getElementById('zipcode').addEventListener('blur', function() {
        let cep = this.value.replace(/\D/g, '');
        if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(res => res.json())
                .then(data => {
                    if (!data.erro) {
                        document.getElementById('address').value = data.logradouro;
                        document.getElementById('neighborhood').value = data.bairro;
                        document.getElementById('city').value = data.localidade;
                        document.getElementById('state').value = data.uf;
                    }
                });
        }
    });
</script>
@endsection
