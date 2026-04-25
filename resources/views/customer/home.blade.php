@extends('layouts.customer')
@section('content')
<main class="px-5 mt-2 space-y-8 pb-32">
    {{-- HERO --}}
    <div class="relative rounded-[2rem] overflow-hidden bg-gray-900 h-48 shadow-lg">
        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800"
             class="absolute inset-0 w-full h-full object-cover opacity-60">
        <div class="absolute inset-0 p-6 flex flex-col justify-center">
            <p class="text-orange-400 text-[10px] font-bold uppercase tracking-widest mb-1">Selamat Datang</p>
            <h2 class="text-2xl font-black text-white leading-tight">Pesan Makanan<br>Langsung di Meja!</h2>
            <a href="{{ route('customer.menu') }}"
               class="mt-4 bg-orange-500 w-fit px-6 py-2 rounded-xl text-[11px] font-bold text-white uppercase tracking-wider shadow-lg active:scale-95 transition">
                Mulai Pesan
            </a>
        </div>
    </div>

    {{-- KATEGORI --}}
    <section>
        <div class="flex justify-center items-center gap-6">
            @foreach($categories as $cat)
                @php
                    $bgColor = 'bg-orange-50';
                    $name = strtolower($cat->name);
                    if (str_contains($name, 'minum')) $bgColor = 'bg-blue-50';
                    if (str_contains($name, 'desert') || str_contains($name, 'dessert')) $bgColor = 'bg-pink-50';
                @endphp
                <a href="{{ route('customer.menu') }}?category={{ $cat->id }}" class="flex flex-col items-center gap-3 active:scale-90 transition group">
                    <div class="{{ $bgColor }} w-16 h-16 rounded-[1.8rem] flex items-center justify-center shadow-sm border border-white p-3">
                        @if($cat->icon)
                            <img src="{{ asset('storage/'.$cat->icon) }}" class="w-full h-full object-contain">
                        @else
                            <i class="fa-solid fa-utensils text-xl text-slate-300"></i>
                        @endif
                    </div>
                    <p class="text-[9px] font-black text-slate-700 uppercase tracking-widest">{{ $cat->name }}</p>
                </a>
            @endforeach
        </div>
    </section>

    {{-- TOP 3 --}}
    <section>
        <h3 class="font-black text-slate-800 text-sm uppercase mb-5 tracking-widest">🏆 Terfavorit</h3>
        <div class="flex overflow-x-auto gap-5 no-scrollbar pb-2">
            @foreach($topMenus as $index => $menu)
            <div class="min-w-[280px] bg-white rounded-[2.5rem] p-5 shadow-xl border border-slate-50 relative {{ $menu->status !== 'available' ? 'opacity-60 grayscale' : '' }}">
                <div class="absolute top-7 left-7 z-10 w-8 h-8 bg-orange-500 text-white flex items-center justify-center rounded-full font-black text-xs shadow-lg">#{{ $index+1 }}</div>
                <img src="{{ asset('storage/'.$menu->image) }}" class="w-full h-40 rounded-[2rem] object-cover mb-4">
                <div class="flex justify-between items-center">
                    <h4 class="font-black text-slate-800 text-sm">{{ $menu->name }}</h4>
                    <p class="text-orange-600 font-black">Rp{{ number_format($menu->price) }}</p>
                </div>
                @if($menu->status === 'available')
                    {{-- ✅ Pakai fungsi global addToCart --}}
                    <button onclick="addToCart({{ $menu->id }})" class="mt-4 w-full bg-slate-900 text-white py-3 rounded-xl font-black text-[10px] uppercase tracking-widest active:scale-95 transition">Tambah</button>
                @else
                    <button disabled class="mt-4 w-full bg-slate-100 text-slate-400 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest">Habis</button>
                @endif
            </div>
            @endforeach
        </div>
    </section>

     {{-- INFO KONTAK --}}
    <div class="bg-[#121826] rounded-[2rem] p-7 mb-10 text-white relative overflow-hidden">
        <div class="relative z-10">
            <h5 class="text-xs font-black uppercase mb-1">Warung Nusantara</h5>
            <p class="text-[9px] text-orange-400 italic mb-4">Cita Rasa Asli Indonesia</p>
            <div class="space-y-2 opacity-70 text-[9px]">
                <div class="flex gap-2"><i class="fa-solid fa-clock"></i> 10.00-22.00 WIB</div>
                <div class="flex gap-2"><i class="fa-solid fa-location-dot"></i> Jl. Sudirman No. 88</div>
            </div>
        </div>
        <i class="fa-solid fa-bowl-food absolute -bottom-5 -right-5 text-8xl text-white/5"></i>
    </div>
</main>
@include('customer.components.mini-cart')
@endsection