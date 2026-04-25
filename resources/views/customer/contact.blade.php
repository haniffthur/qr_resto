@extends('layouts.customer')

@section('content')
<main class="px-6 py-8 pb-40 animate__animated animate__fadeIn">
    {{-- Header Section --}}
    <div class="mb-10">
        <h2 class="text-3xl font-black text-slate-800 tracking-tighter leading-none">Bantuan <span class="text-orange-500">&</span> Support</h2>
        
    </div>

    {{-- Main Support Card (WhatsApp) --}}
    <a href="https://wa.me/628123456789" class="block group relative bg-white border border-slate-100 rounded-[2.5rem] p-1 mb-8 shadow-xl shadow-slate-200/40 active:scale-95 transition-all duration-300">
        <div class="bg-slate-50 rounded-[2.2rem] p-6 flex items-center gap-5">
            <div class="w-16 h-16 bg-emerald-500 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-200 group-hover:rotate-12 transition-transform">
                <i class="fa-brands fa-whatsapp text-3xl text-white"></i>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-black text-slate-800 uppercase tracking-tight">Hubungi Admin</h4>
                <p class="text-[10px] text-slate-400 font-bold mt-0.5">Respon super cepat via WhatsApp</p>
            </div>
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-slate-300">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </div>
        </div>
    </a>

    {{-- Info Cards Grid --}}
    <div class="grid grid-cols-2 gap-4 mb-8">
        {{-- Jam Operasional --}}
        <div class="bg-orange-50/50 border border-orange-100 p-6 rounded-[2.2rem]">
            <div class="w-10 h-10 bg-orange-500/10 text-orange-600 rounded-xl flex items-center justify-center mb-4">
                <i class="fa-solid fa-clock text-sm"></i>
            </div>
            <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Operasional</h5>
            <p class="text-[11px] font-black text-slate-800 mt-1 uppercase">10:00 — 22:00</p>
        </div>

        {{-- Email --}}
        <div class="bg-blue-50/50 border border-blue-100 p-6 rounded-[2.2rem]">
            <div class="w-10 h-10 bg-blue-500/10 text-blue-600 rounded-xl flex items-center justify-center mb-4">
                <i class="fa-solid fa-envelope text-sm"></i>
            </div>
            <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email Resmi</h5>
            <p class="text-[11px] font-black text-slate-800 mt-1">halo@wn.com</p>
        </div>
    </div>

    {{-- Alamat Section --}}
    <div class="bg-[#121826] rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl shadow-slate-900/20 mb-8">
        <div class="relative z-10">
            <span class="inline-block bg-orange-500 text-[9px] font-black uppercase tracking-[0.2em] px-3 py-1 rounded-lg mb-4">Lokasi Fisik</span>
            <h4 class="text-lg font-black tracking-tight leading-snug mb-2">Warung Nusantara Central</h4>
            <p class="text-xs text-slate-400 font-medium leading-relaxed mb-6">Jl. Sudirman No. 88, Kavling 12, Jakarta Selatan. 12190</p>
            
            <a href="https://maps.google.com" class="flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-orange-500 hover:text-white transition">
                Petunjuk Arah <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        {{-- Background Icon Decoration --}}
        <i class="fa-solid fa-map-location-dot absolute -bottom-6 -right-6 text-9xl text-white/5 rotate-12"></i>
    </div>

    {{-- Social Media --}}
    <div class="flex justify-center items-center gap-8 pt-4">
        <a href="#" class="text-slate-300 hover:text-pink-500 transition-colors text-xl"><i class="fa-brands fa-instagram"></i></a>
        <a href="#" class="text-slate-300 hover:text-slate-900 transition-colors text-xl"><i class="fa-brands fa-tiktok"></i></a>
        <a href="#" class="text-slate-300 hover:text-blue-500 transition-colors text-xl"><i class="fa-brands fa-facebook-f"></i></a>
    </div>

    <p class="text-center text-[9px] font-bold text-slate-300 uppercase tracking-[0.3em] mt-12">© 2026 Warung Nusantara</p>
</main>

@include('customer.components.mini-cart')
@endsection