@extends('layouts.admin')

@section('title', 'Edit Menu - Panel Bos')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.menus.index') }}" class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-400 hover:text-orange-500 transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Edit <span class="text-orange-500">Menu</span></h2>
    </div>

    <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Menu</label>
                <input type="text" name="name" value="{{ $menu->name }}" required class="w-full border border-slate-200 p-4 rounded-2xl mt-1 font-bold text-slate-700 outline-none focus:ring-2 focus:ring-orange-500 transition">
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kategori</label>
                <select name="category_id" required class="w-full border border-slate-200 p-4 rounded-2xl mt-1 font-bold text-slate-700 outline-none focus:ring-2 focus:ring-orange-500 transition appearance-none bg-white">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $menu->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Harga (Rp)</label>
                <input type="number" name="price" value="{{ (int)$menu->price }}" required class="w-full border border-slate-200 p-4 rounded-2xl mt-1 font-bold text-slate-700 outline-none focus:ring-2 focus:ring-orange-500 transition">
            </div>

            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Ubah Foto (Biarkan kosong jika tidak diubah)</label>
                <div class="flex items-center gap-6 mt-2">
                    <div class="w-24 h-24 rounded-2xl overflow-hidden shadow-md shrink-0">
                        <img src="{{ asset('storage/'.$menu->image) }}" class="w-full h-full object-cover">
                    </div>
                    <input type="file" name="image" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-orange-50 file:text-orange-500">
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-[2rem] font-black uppercase text-sm tracking-[0.2em] shadow-xl shadow-slate-200 active:scale-95 transition">
            Update Menu ✅
        </button>
    </form>
</div>
@endsection