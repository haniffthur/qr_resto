<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kasir - Resto')</title>
    @stack('head')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen font-sans selection:bg-orange-500 selection:text-white">

    <header class="bg-white border-b border-gray-200 p-4 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="bg-orange-500 text-white p-2 rounded-lg flex items-center justify-center shadow-md shadow-orange-500/20">
                    <i class="fa-solid fa-cash-register"></i>
                </div>
                <h1 class="text-xl font-black text-slate-800 uppercase tracking-tight">
                    POS <span class="text-orange-500">Kasir</span>
                </h1>
                
                <nav class="hidden md:flex space-x-6 ml-8 border-l border-gray-200 pl-8 text-sm font-semibold">
                    <a href="{{ route('kasir.dashboard') }}" class="{{ request()->routeIs('kasir.dashboard') ? 'text-orange-600 border-b-2 border-orange-600 pb-1' : 'text-slate-500 hover:text-orange-500 transition pb-1' }}">
                        <i class="fa-solid fa-list-ul mr-1"></i> Antrean
                    </a>
                    <a href="{{ route('kasir.history') }}" class="{{ request()->routeIs('kasir.history') ? 'text-orange-600 border-b-2 border-orange-600 pb-1' : 'text-slate-500 hover:text-orange-500 transition pb-1' }}">
                        <i class="fa-solid fa-clock-rotate-left mr-1"></i> Riwayat
                    </a>
                    <a href="{{ route('kasir.pos') }}" class="{{ request()->routeIs('kasir.pos') ? 'text-orange-600 border-b-2 border-orange-600 pb-1' : 'text-slate-500 hover:text-orange-500 transition pb-1' }}">
    <i class="fa-solid fa-bag-shopping mr-1"></i> Take Away
</a>
                </nav>
            </div>

            <div class="flex items-center space-x-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Petugas Kasir</p>
                    <p class="text-sm font-black text-slate-800">{{ auth()->user()->name }}</p>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-slate-100 hover:bg-red-50 text-slate-600 hover:text-red-600 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition flex items-center border border-slate-200 hover:border-red-200">
                        <i class="fa-solid fa-power-off mr-2"></i> Logout
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