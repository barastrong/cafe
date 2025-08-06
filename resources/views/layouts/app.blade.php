<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-gray-50 text-gray-800">
    <div class="min-h-screen flex flex-col">

        <nav class="bg-white/80 backdrop-blur-sm shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    
                    <div class="flex items-center space-x-6">
                        <a href="/" class="font-bold text-xl text-blue-600">
                            NamaKafe
                        </a>
                        <div class="hidden sm:flex space-x-6">
                            <a href="#" class="text-gray-600 font-medium hover:text-blue-600 transition-colors duration-200">Menu</a>
                            <a href="#" class="text-gray-600 font-medium hover:text-blue-600 transition-colors duration-200">Promo</a>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center space-x-4">
                        <a href="#" class="text-gray-600 hover:text-blue-600 p-2 rounded-full hover:bg-gray-100 transition-colors duration-200">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.658-.463 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                        </a>
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.index') }}" class="text-purple-600 hover:text-purple-800 p-2 rounded-full hover:bg-purple-50 transition-colors duration-200" title="Admin Panel">
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.286zm0 13.036h.008v.008h-.008v-.008z" /></svg>
                                </a>
                            @endif

                            <span class="text-sm font-medium text-gray-700">Hi, {{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm font-semibold text-red-600 hover:text-white hover:bg-red-600 border border-red-200 hover:border-red-600 px-3 py-1.5 rounded-md transition-all duration-200">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md transition-colors duration-200">Login</a>
                            <a href="{{ route('register') }}" class="text-sm font-semibold text-gray-700 hover:bg-gray-100 px-4 py-2 rounded-md transition-colors duration-200">Daftar</a>
                        @endauth
                    </div>

                    <div class="sm:hidden flex items-center">
                        <button id="hamburger-button" class="text-gray-500 hover:text-gray-700 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                        </button>
                    </div>

                </div>
            </div>

            <div id="mobile-menu" class="hidden sm:hidden border-t border-gray-200">
                <div class="py-2 space-y-1">
                    <a href="#" class="block py-2 px-4 text-base font-medium text-gray-600 hover:bg-blue-50 hover:text-blue-700">Menu</a>
                    <a href="#" class="block py-2 px-4 text-base font-medium text-gray-600 hover:bg-blue-50 hover:text-blue-700">Promo</a>
                    <a href="#" class="block py-2 px-4 text-base font-medium text-gray-600 hover:bg-blue-50 hover:text-blue-700">Keranjang</a>
                </div>
                <div class="border-t border-gray-200 pt-4 pb-3">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.index') }}" class="flex items-center py-2 px-4 font-semibold text-purple-600 hover:bg-purple-50">
                                <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.286zm0 13.036h.008v.008h-.008v-.008z" /></svg>
                                Admin Panel
                            </a>
                        @endif
                        <div class="flex justify-between items-center px-4 mt-3">
                            <span class="font-medium text-gray-800">Hi, {{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm font-semibold text-red-600">Logout</button>
                            </form>
                        </div>
                    @else
                        <div class="px-4 space-y-2">
                             <a href="{{ route('login') }}" class="block w-full text-center text-base font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md transition-colors duration-200">Login</a>
                             <a href="{{ route('register') }}" class="block w-full text-center text-base font-semibold text-gray-700 hover:bg-gray-100 px-4 py-2 rounded-md transition-colors duration-200">Daftar</a>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        <main class="flex-grow">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
                    @yield('content')
                </div>
            </div>
        </main>

        <footer class="bg-white border-t">
            <div class="max-w-7xl mx-auto py-8 px-4 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} NamaKafe. All Rights Reserved.
            </div>
        </footer>

    </div>

    <script>
        const hamburgerButton = document.getElementById('hamburger-button');
        const mobileMenu = document.getElementById('mobile-menu');

        hamburgerButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>