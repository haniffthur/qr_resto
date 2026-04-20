@extends('layouts.admin')

@section('title', 'Tambah Menu Baru - Admin')

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-200">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-800">Tambah Menu Baru</h2>
            <a href="{{ route('admin.menus.index') }}" class="text-gray-500 hover:text-gray-800 font-medium">&larr; Batal & Kembali</a>
        </div>

        <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Menu</label>
                <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none" placeholder="Contoh: Ayam Bakar Madu">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                    <select name="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none">
                        <option value="">Pilih Kategori...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Harga (Rp)</label>
                    <input type="number" name="price" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none" placeholder="Contoh: 25000">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none" placeholder="Jelaskan menu ini..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Foto Menu</label>
                <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_available" id="is_available" value="1" checked class="w-5 h-5 text-orange-600 rounded focus:ring-orange-500">
                <label for="is_available" class="ml-2 font-medium text-gray-700">Menu Tersedia (Siap Dijual)</label>
            </div>

            <div class="pt-4 border-t">
                <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 rounded-lg shadow-lg transition">
                    Simpan Menu Baru
                </button>
            </div>
        </form>
    </div>
@endsection