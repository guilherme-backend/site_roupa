@extends('layouts.shop')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Meus Endereços</h1>
        <a href="{{ route('profile.addresses.create') }}" class="btn-primary">
            Adicionar Novo Endereço
        </a>
    </div>

    @if($addresses->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <p class="text-gray-500 text-lg">Você ainda não tem endereços cadastrados.</p>
        </div>
    @else
        <div class="grid gap-6">
            @foreach($addresses as $address)
                <div class="bg-white rounded-2xl shadow-sm border {{ $address->is_default ? 'border-black' : 'border-gray-100' }} p-6 transition hover:shadow-md">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-bold text-gray-900">{{ $address->recipient_name }}</span>
                                @if($address->label)
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full uppercase">{{ $address->label }}</span>
                                @endif
                                @if($address->is_default)
                                    <span class="text-xs bg-black text-white px-2 py-0.5 rounded-full">Padrão</span>
                                @endif
                            </div>
                            <p class="text-gray-600 text-sm">{{ $address->full_address }}</p>
                        </div>
                        <div class="flex gap-4 ml-4">
                            @if(!$address->is_default)
                                <form action="{{ route('profile.addresses.default', $address) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-sm text-gray-500 hover:text-black font-medium">Tornar Padrão</button>
                                </form>
                            @endif
                            <a href="{{ route('profile.addresses.edit', $address) }}" class="text-sm text-gray-500 hover:text-black font-medium">Editar</a>
                            <form action="{{ route('profile.addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('Tem certeza?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium">Excluir</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
