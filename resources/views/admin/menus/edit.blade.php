@extends('layouts.admin')

@section('title', 'Edit Menu - ' . $menu->name)

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-200">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-800">Edit Menu</h2>
            <a href="{{ route('admin.menus.index') }}" class="text-gray-500 hover:text-gray-800 font-medium">&larr; Batal & Kembali</a>
        </div>

        <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Menu</label>
                <input type="text" name="name" value="{{ $menu->name }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                    <select name="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $menu->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ $menu->price }}" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:outline-none">{{ $menu->description }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Foto Menu</label>
                @if($menu->image)
                    <div class="mb-3 flex items-center space-x-4">
                        <img src="{{ asset('storage/' . $menu->image) }}" class="w-20 h-20 object-cover rounded-lg border shadow-sm">
                        <span class="text-xs text-gray-500">Foto yang sedang digunakan</span>
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                <p class="text-xs text-gray-400 mt-1">*Kosongkan jika tidak ingin mengubah foto</p>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_available" id="is_available" value="1" {{ $menu->is_available ? 'checked' : '' }} class="w-5 h-5 text-orange-600 rounded focus:ring-orange-500">
                <label for="is_available" class="ml-2 font-medium text-gray-700">Menu Tersedia (Siap Dijual)</label>
            </div>

            <div class="pt-4 border-t">
                <button type="submit" class="w-full bg-black hover:bg-gray-800 text-white font-bold py-3 rounded-lg shadow-lg transition text-center">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection