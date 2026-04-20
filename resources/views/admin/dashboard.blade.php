@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-8">Ringkasan Hari Ini</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-gray-400 text-xs font-bold uppercase">Pesanan Baru</p>
            <h3 class="text-3xl font-black mt-2">{{ $stats['total_orders'] }}</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-gray-400 text-xs font-bold uppercase">Pendapatan (Paid)</p>
            <h3 class="text-3xl font-black mt-2 text-green-600">Rp {{ number_format($stats['total_revenue']) }}</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-gray-400 text-xs font-bold uppercase">Antrean Dapur</p>
            <h3 class="text-3xl font-black mt-2 text-red-500">{{ $stats['pending_orders'] }}</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-gray-400 text-xs font-bold uppercase">Meja Kosong</p>
            <h3 class="text-3xl font-black mt-2 text-blue-600">{{ $stats['available_tables'] }}</h3>
        </div>
    </div>

    <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('admin.menus.create') }}" class="bg-black text-white p-8 rounded-3xl flex items-center justify-between hover:scale-[1.02] transition">
            <span class="text-xl font-bold">Tambah Menu Baru</span>
            <span class="text-3xl">&rarr;</span>
        </a>
        <a href="{{ route('admin.tables.index') }}" class="bg-orange-500 text-white p-8 rounded-3xl flex items-center justify-between hover:scale-[1.02] transition">
            <span class="text-xl font-bold">Kelola Meja & QR</span>
            <span class="text-3xl">&rarr;</span>
        </a>
    </div>
@endsection