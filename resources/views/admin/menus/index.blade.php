@extends('layouts.admin')

@section('title', 'Daftar Menu - Panel Bos')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Manajemen <span class="text-orange-500">Menu</span> 🍔</h2>
        <p class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-widest">Total Menu: {{ $menus->count() }}</p>
    </div>
    <a href="{{ route('admin.menus.create') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-orange-200 transition active:scale-95">
        + Tambah Menu
    </a>
</div>

@if(session('success'))
    <div class="bg-emerald-50 text-emerald-600 p-4 rounded-2xl mb-6 border border-emerald-100 font-bold text-sm animate__animated animate__fadeIn">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-slate-50 border-b border-gray-100 text-slate-400 text-[10px] font-black uppercase tracking-widest">
            <tr>
                <th class="px-8 py-5">Foto & Nama</th>
                <th class="px-6 py-5">Kategori</th>
                <th class="px-6 py-5">Harga</th>
                <th class="px-6 py-5">Status Stok</th>
                <th class="px-6 py-5 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($menus as $menu)
            <tr class="hover:bg-slate-50/50 transition">
                <td class="px-8 py-5">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl overflow-hidden shadow-sm border border-white">
                            <img src="{{ asset('storage/'.$menu->image) }}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/100x100?text=WN'">
                        </div>
                        <div>
                            <p class="font-black text-slate-700 text-sm tracking-tight">{{ $menu->name }}</p>
                            <p class="text-[10px] text-slate-400 italic">ID: #{{ $menu->id }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-5">
                    <span class="bg-slate-100 text-slate-500 text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-tighter">
                        {{ $menu->category->name ?? 'Tanpa Kategori' }}
                    </span>
                </td>
                <td class="px-6 py-5">
                    <p class="text-orange-600 font-black text-sm tracking-tighter">Rp{{ number_format($menu->price, 0, ',', '.') }}</p>
                </td>
                <td class="px-6 py-5">
                    <form action="{{ route('admin.menus.toggleStatus', $menu->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="flex items-center gap-2 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest transition-all
                            {{ $menu->status === 'available' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-red-50 text-red-600 border border-red-100' }}">
                            <div class="w-1.5 h-1.5 rounded-full {{ $menu->status === 'available' ? 'bg-emerald-500' : 'bg-red-500' }}"></div>
                            {{ $menu->status === 'available' ? 'Tersedia' : 'Habis' }}
                        </button>
                    </form>
                </td>
                <td class="p-4 text-right flex justify-end space-x-3 mt-2">
                        <a href="{{ route('admin.menus.edit', $menu->id) }}" class="text-blue-600 hover:bg-blue-50 px-3 py-1 rounded transition">Edit</a>
                        <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Hapus menu?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:bg-red-50 px-3 py-1 rounded transition">Hapus</button>
                        </form>
                    </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-8 py-20 text-center">
                    <p class="text-slate-300 font-bold italic uppercase tracking-widest text-xs">Belum ada menu yang ditambahkan</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection