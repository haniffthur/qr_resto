<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kasir - Resto')</title>
    @stack('head')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen font-sans">

    <header class="bg-gray-800 border-b border-gray-700 p-4 sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
    <h1 class="text-2xl font-bold text-orange-500">POS Kasir</h1>
    <nav class="hidden md:flex space-x-4 ml-6 border-l border-gray-700 pl-6 text-sm">
        <a href="{{ route('kasir.dashboard') }}" class="{{ request()->routeIs('kasir.dashboard') ? 'text-white font-bold' : 'text-gray-400 hover:text-white' }}">Antrean</a>
        <a href="{{ route('kasir.history') }}" class="{{ request()->routeIs('kasir.history') ? 'text-white font-bold' : 'text-gray-400 hover:text-white' }}">Riwayat</a>
    </nav>
</div>

            <div class="flex items-center space-x-6">
                <div class="text-right hidden sm:block">
                    <p class="text-xs text-gray-400 font-bold uppercase">Petugas</p>
                    <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-6">
        @yield('content')
    </main>

</body>
</html>