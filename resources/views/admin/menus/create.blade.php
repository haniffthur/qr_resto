@extends('layouts.admin')

@section('title', 'Tambah Menu Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.menus.index') }}" class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-400 hover:text-orange-500 transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Tambah <span class="text-orange-500">Menu</span> Baru</h2>
    </div>

    <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Menu</label>
                <input type="text" name="name" required placeholder="Contoh: Nasi Goreng Gila" class="w-full border border-slate-200 p-4 rounded-2xl mt-1 font-bold text-slate-700 outline-none focus:ring-2 focus:ring-orange-500 transition">
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kategori</label>
                <select name="category_id" required class="w-full border border-slate-200 p-4 rounded-2xl mt-1 font-bold text-slate-700 outline-none focus:ring-2 focus:ring-orange-500 transition appearance-none bg-white">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Harga (Rp)</label>
                <input type="number" name="price" required placeholder="Contoh: 25000" class="w-full border border-slate-200 p-4 rounded-2xl mt-1 font-bold text-slate-700 outline-none focus:ring-2 focus:ring-orange-500 transition">
            </div>

            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Deskripsi Singkat</label>
                <textarea name="description" rows="3" placeholder="Jelaskan kelezatan menu ini..." class="w-full border border-slate-200 p-4 rounded-2xl mt-1 font-bold text-slate-700 outline-none focus:ring-2 focus:ring-orange-500 transition"></textarea>
            </div>

            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Foto Menu (JPG/PNG)</label>
                <div class="mt-1 border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center hover:border-orange-500 transition group relative">
                    <input type="file" name="image" required class="absolute inset-0 opacity-0 cursor-pointer">
                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-300 group-hover:text-orange-500 mb-2 transition"></i>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Klik atau seret foto ke sini</p>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-[2rem] font-black uppercase text-sm tracking-[0.2em] shadow-xl shadow-slate-200 active:scale-95 transition">
            Simpan Menu 🚀
        </button>
    </form>
</div>
@endsection