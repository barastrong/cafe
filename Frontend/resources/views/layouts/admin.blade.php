<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'Laravel') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body class="bg-gray-100 font-sans">

    <div class="relative min-h-screen md:flex">

        <div id="mobile-menu" class="bg-gray-800 text-white w-64 space-y-4 py-7 px-3 absolute inset-y-0 left-0 transform -translate-x-full md:hidden transition duration-300 ease-in-out z-30">
            <a href="{{ route('admin.index') }}" class="text-white flex items-center space-x-3 px-4">
                <i class="fa-solid fa-shield-halved fa-2x text-blue-400"></i>
                <span class="text-2xl font-extrabold">Admin Panel</span>
            </a>
            <nav class="space-y-2">
                <a href="{{ route('admin.index') }}" class="flex items-center py-2 px-4 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.index') ? 'bg-blue-600 text-white font-semibold' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fa-solid fa-house w-6 mr-3"></i> Home
                </a>
                <a href="{{ route('admin.products') }}" class="flex items-center py-2 px-4 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.products') ? 'bg-blue-600 text-white font-semibold' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fa-solid fa-box-archive w-6 mr-3"></i> Products
                </a>
                <a href="{{ route('admin.menus') }}" class="flex items-center py-2 px-4 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.menus') ? 'bg-blue-600 text-white font-semibold' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fa-solid fa-utensils w-6 mr-3"></i> Menus
                </a>
                <a href="{{ route('admin.promos') }}" class="flex items-center py-2 px-4 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.promos') ? 'bg-blue-600 text-white font-semibold' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fa-solid fa-tag w-6 mr-3"></i> Promos
                </a>
                <a href="{{ route('admin.chat') }}" class="flex items-center py-2 px-4 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.chat') ? 'bg-blue-600 text-white font-semibold' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fa-solid fa-robot w-6 mr-3"></i> Chat AI
                </a>
            </nav>
        </div>

        <aside class="bg-gray-800 text-gray-100 w-64 space-y-6 py-7 px-4 hidden md:flex md:flex-col">
            <a href="{{ route('admin.index') }}" class="text-white flex items-center space-x-3 px-2">
                <i class="fa-solid fa-shield-halved fa-2x text-blue-400"></i>
                <span class="text-2xl font-extrabold">Admin Panel</span>
            </a>
            <nav class="flex-grow space-y-2">
                <a href="{{ route('admin.index') }}" class="flex items-center py-2.5 px-4 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.index') ? 'bg-blue-600 text-white font-semibold' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fa-solid fa-house w-6 mr-3"></i> Home
                </a>
                <a href="{{ route('admin.products') }}" class="flex items-center py-2.5 px-4 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.products') ? 'bg-blue-600 text-white font-semibold' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fa-solid fa-box-archive w-6 mr-3"></i> Products
                </a>
                <a href="{{ route('admin.menus') }}" class="flex items-center py-2.5 px-4 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.menus') ? 'bg-blue-600 text-white font-semibold' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fa-solid fa-utensils w-6 mr-3"></i> Menus
                </a>
                <a href="{{ route('admin.promos') }}" class="flex items-center py-2.5 px-4 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.promos') ? 'bg-blue-600 text-white font-semibold' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fa-solid fa-tag w-6 mr-3"></i> Promos
                </a>
                <a href="{{ route('admin.chat') }}" class="flex items-center py-2.5 px-4 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.chat') ? 'bg-blue-600 text-white font-semibold' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fa-solid fa-robot w-6 mr-3"></i> Chat AI
                </a>
            </nav>
            <div>
                 <a href="#" class="flex items-center py-2.5 px-4 rounded-md transition-colors duration-200 text-gray-400 hover:bg-red-800 hover:text-white">
                    <i class="fa-solid fa-arrow-right-from-bracket w-6 mr-3"></i> Logout
                </a>
            </div>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm p-4 flex justify-between items-center">
                <button id="hamburger-button" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fa-solid fa-bars text-2xl"></i>
                </button>
                <h1 class="text-xl font-semibold text-gray-800 hidden md:block">
                    @yield('title', 'Dashboard')
                </h1>
                <div class="flex items-center space-x-3">
                    <span class="font-medium text-gray-700 hidden sm:block">Hi, Admin</span>
                    <img class="h-10 w-10 rounded-full object-cover ring-2 ring-offset-2 ring-blue-500" src="https://ui-avatars.com/api/?name=Admin&background=3b82f6&color=fff&bold=true" alt="User avatar">
                </div>
            </header>

            <main class="flex-1 p-6 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
    <script>
        const hamburgerButton = document.getElementById('hamburger-button');
        const mobileMenu = document.getElementById('mobile-menu');

        hamburgerButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('-translate-x-full');
        });
    </script>
</body>
</html>