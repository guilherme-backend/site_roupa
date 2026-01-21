<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'E-commerce Roupas') }} - @yield('title', 'Loja Online')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('shop.index') }}" class="text-2xl font-bold text-gray-900">
                                {{ config('app.name', 'E-commerce') }}
                            </a>
                        </div>

                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('shop.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('shop.index') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Início
                            </a>
                            
                            <a href="{{ route('shop.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('shop.*') && !request()->routeIs('shop.index') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Loja
                            </a>
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <a href="{{ route('cart.index') }}" class="text-gray-500 hover:text-gray-700 p-2 relative">
                            <span class="sr-only">Carrinho</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @if(session('cart') && count(session('cart')) > 0)
                                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full">
                                    {{ count(session('cart')) }}
                                </span>
                            @endif
                        </a>

                        @auth
                            <div class="ml-3 relative" x-data="{ open: false }">
                                <div>
                                    <button @click="open = ! open" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                        <span class="text-gray-500">{{ Auth::user()->name }}</span>
                                        <svg class="ml-1 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                                <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" style="display: none;">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Sair
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 underline ml-4">Entrar</a>
                            <a href="{{ route('register') }}" class="text-sm text-gray-700 underline ml-4">Registrar</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main>
            @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="bg-gray-800 text-white mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">{{ config('app.name') }}</h3>
                        <p class="text-gray-400">E-commerce de roupas com as melhores marcas e preços.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Links Rápidos</h3>
                        <ul class="space-y-2 text-gray-400">
                            {{-- CORREÇÃO FINAL REALIZADA AQUI: mudamos de route('home') para route('shop.index') --}}
                            <li><a href="{{ route('shop.index') }}" class="hover:text-white">Início</a></li>
                            <li><a href="{{ route('shop.index') }}" class="hover:text-white">Loja</a></li>
                            <li><a href="{{ route('cart.index') }}" class="hover:text-white">Carrinho</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Contato</h3>
                        <p class="text-gray-400">Email: contato@ecommerce.com</p>
                        <p class="text-gray-400">Telefone: (11) 1234-5678</p>
                    </div>
                </div>
                <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>