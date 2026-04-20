@extends('layouts.admin')

@section('title', 'Manajemen Menu - Admin')

@section('content')
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative flex justify-between items-center">
        <span class="font-medium">{{ session('success') }}</span>
        <button @click="show = false" class="text-green-700 font-bold">&times;</button>
    </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Daftar Menu Resto</h2>
        <a href="{{ route('admin.menus.create') }}" class="bg-black text-white px-5 py-2.5 rounded-lg font-bold shadow-md hover:bg-gray-800 transition">
            + Tambah Menu
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 uppercase text-gray-600">
                    <th class="p-4 font-semibold">Menu & Deskripsi</th>
                    <th class="p-4 font-semibold">Kategori</th>
                    <th class="p-4 font-semibold">Harga</th>
                    <th class="p-4 font-semibold text-center">Status</th>
                    <th class="p-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($menus as $menu)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 flex items-center space-x-4">
                        @if($menu->image)
                            <div class="w-12 h-12 bg-gray-200 rounded-lg bg-cover bg-center shadow-sm" style="background-image: url('{{ asset('storage/'.$menu->image) }}')"></div>
                        @else
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 border border-gray-200">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                        @endif
                        <div>
                            <p class="font-bold text-gray-800">{{ $menu->name }}</p>
                            <p class="text-xs text-gray-500 truncate w-48">{{ $menu->description ?: 'No description' }}</p>
                        </div>
                    </td>
                    <td class="p-4 text-gray-600 font-medium">{{ $menu->category->name ?? 'N/A' }}</td>
                    <td class="p-4 font-bold text-orange-600">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                    <td class="p-4 text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $menu->is_available ? 'bg-green-100 text-green-700 border-green-200' : 'bg-red-100 text-red-700 border-red-200' }}">
                            {{ $menu->is_available ? 'Tersedia' : 'Habis' }}
                        </span>
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
                <tr><td colspan="5" class="p-8 text-center text-gray-500">Belum ada menu.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection