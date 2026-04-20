<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Bos')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen font-sans">

    <nav class="bg-white shadow-sm px-6 py-4 flex justify-between items-center sticky top-0 z-10">
        <h1 class="text-2xl font-bold text-gray-800">Panel Bos 👑</h1>
        <div class="flex items-center space-x-6">
            <div class="hidden md:flex space-x-4">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'text-orange-600 font-bold border-b-2 border-orange-600 pb-1' : 'text-gray-500 font-medium hover:text-orange-600 transition' }}">Dashboard</a>
                <a href="{{ route('admin.menus.index') }}" class="{{ request()->routeIs('admin.menus.*') ? 'text-orange-600 font-bold border-b-2 border-orange-600 pb-1' : 'text-gray-500 font-medium hover:text-orange-600 transition' }}">Menu</a>
                <a href="{{ route('admin.tables.index') }}" class="{{ request()->routeIs('admin.tables.*') ? 'text-orange-600 font-bold border-b-2 border-orange-600 pb-1' : 'text-gray-500 font-medium hover:text-orange-600 transition' }}">Meja</a>
                <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'text-orange-600 font-bold border-b-2 border-orange-600 pb-1' : 'text-gray-500 font-medium hover:text-orange-600 transition' }}">Laporan</a>
            </div>
            
            <div class="flex items-center space-x-4 border-l pl-4">
                <span class="text-gray-600 font-medium">{{ auth()->user()->name ?? 'Admin' }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-lg font-medium transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-8">
        @yield('content')
    </main>

</body>
</html>