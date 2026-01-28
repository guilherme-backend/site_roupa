<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Loja de Roupas'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">
    
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <a href="{{ route('shop.index') }}" class="font-bold text-2xl text-indigo-600 tracking-tight">
                        MODA<span class="text-gray-900">URBANA</span>
                    </a>
                </div>
                
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="{{ route('shop.index') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition">Início</a>
                    <a href="#" class="text-gray-600 hover:text-indigo-600 font-medium transition">Categorias</a>
                    <a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition relative">
                        Carrinho
                        <span class="absolute -top-2 -right-3 bg-indigo-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">0</span>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">Minha Conta</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">Entrar</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">Cadastrar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="min-h-screen">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-200 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="col-span-1 md:col-span-2">
                <a href="{{ route('shop.index') }}" class="font-bold text-2xl text-indigo-600 tracking-tight">
                    MODA<span class="text-gray-900">URBANA</span>
                </a>
                <p class="mt-4 text-gray-500 max-w-xs">
                    Sua loja de moda favorita, com as melhores tendências e preços do mercado brasileiro.
                </p>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 mb-4">Links Úteis</h3>
                <ul class="space-y-2 text-gray-500">
                    <li><a href="#" class="hover:text-indigo-600 transition">Sobre Nós</a></li>
                    <li><a href="#" class="hover:text-indigo-600 transition">Contato</a></li>
                    <li><a href="#" class="hover:text-indigo-600 transition">Privacidade</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 mb-4">Redes Sociais</h3>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-indigo-600 transition">Instagram</a>
                    <a href="#" class="text-gray-400 hover:text-indigo-600 transition">Facebook</a>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-gray-100 text-center text-gray-400 text-sm">
            <p>&copy; {{ date('Y') }} MODA URBANA. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>
