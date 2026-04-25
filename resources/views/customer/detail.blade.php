@extends('layouts.customer')
@section('title', $menu->name)
@section('content')
<div class="relative min-h-screen bg-slate-50 pb-32">
    <div class="relative h-[45vh]">
        <img src="{{ asset('storage/'.$menu->image) }}" class="w-full h-full object-cover {{ $menu->status !== 'available' ? 'grayscale opacity-50' : '' }}">
        <a href="{{ route('customer.menu') }}" class="absolute top-6 left-6 w-10 h-10 bg-white/90 backdrop-blur rounded-2xl flex items-center justify-center shadow-xl"><i class="fa-solid fa-chevron-left text-slate-800"></i></a>
    </div>

    <div class="px-8 -mt-12 bg-white rounded-t-[3.5rem] pt-10 relative z-10 shadow-2xl min-h-[60vh]">
        <span class="text-orange-500 text-[9px] font-black uppercase tracking-widest bg-orange-50 px-3 py-1.5 rounded-lg">{{ $menu->category->name }}</span>
        <h1 class="text-2xl font-black text-slate-800 mt-4 tracking-tight">{{ $menu->name }}</h1>
        <p class="text-orange-600 font-black text-xl mt-2">Rp{{ number_format($menu->price) }}</p>

        <div class="mt-8">
            <h3 class="font-black text-slate-400 text-[10px] uppercase tracking-widest mb-3">Deskripsi</h3>
            <p class="text-xs text-slate-500 leading-relaxed italic">{{ $menu->description ?? 'Nikmati bumbu rahasia Warung Nusantara.' }}</p>
        </div>
    </div>
</div>

<div class="fixed bottom-0 inset-x-0 bg-white/90 backdrop-blur-xl px-8 py-8 border-t border-slate-100 z-50">
    @if($menu->status === 'available')
        {{-- ✅ Tombol Pesan langsung panggil addToCart global --}}
        <button onclick="addToCart({{ $menu->id }})" class="w-full bg-slate-900 text-white py-5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl active:scale-95 transition">
            Tambah ke Keranjang 🍽️
        </button>
    @else
        <button disabled class="w-full bg-slate-100 text-slate-300 py-5 rounded-2xl font-black text-xs uppercase tracking-widest cursor-not-allowed border border-slate-200">
            Stok Sedang Habis
        </button>
    @endif
</div>

@include('customer.components.mini-cart')
@endsection