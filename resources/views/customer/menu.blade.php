@extends('layouts.customer')
@section('content')
<main class="px-5 py-6 pb-32">
    <div class="sticky top-[72px] bg-white/90 backdrop-blur-md z-[90] pb-4 -mx-5 px-5">
        <input type="text" id="search-input" placeholder="Lagi pengen apa, Nif?" 
               class="w-full px-6 py-4 bg-slate-100 border-none rounded-2xl text-xs font-bold outline-none focus:ring-2 focus:ring-orange-500 transition-all">
    </div>

    @foreach($categories as $category)
    <section class="mb-10 category-section" data-category="{{ $category->id }}">
        <h3 class="font-black text-slate-400 text-[10px] uppercase tracking-widest mb-6 border-l-4 border-orange-500 pl-3">{{ $category->name }}</h3>
        <div class="grid grid-cols-2 gap-5">
            @foreach($category->menus as $menu)
            <div class="menu-card bg-white rounded-[2rem] p-3 border border-slate-50 shadow-sm {{ $menu->status !== 'available' ? 'opacity-60' : '' }}" data-name="{{ strtolower($menu->name) }}">
                <a href="{{ route('customer.menu.detail', $menu->id) }}" class="block relative aspect-square rounded-[1.5rem] overflow-hidden mb-3">
                    <img src="{{ asset('storage/'.$menu->image) }}" class="w-full h-full object-cover">
                    @if($menu->status !== 'available')
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center"><span class="text-white text-[8px] font-black uppercase">Sold Out</span></div>
                    @endif
                </a>
                <h4 class="text-[11px] font-black text-slate-800 truncate">{{ $menu->name }}</h4>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-orange-600 font-black text-[11px]">Rp{{ number_format($menu->price) }}</span>
                    @if($menu->status === 'available')
                        {{-- ✅ Pakai fungsi global addToCart --}}
                        <button onclick="addToCart({{ $menu->id }})" class="w-8 h-8 bg-slate-900 text-white rounded-xl flex items-center justify-center active:scale-90 transition"><i class="fa-solid fa-plus text-[9px]"></i></button>
                    @else
                        <div class="w-8 h-8 bg-slate-100 text-slate-300 rounded-xl flex items-center justify-center"><i class="fa-solid fa-ban text-[9px]"></i></div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endforeach

    <div id="search-empty" class="hidden py-20 text-center">
        <i class="fa-solid fa-face-frown text-5xl text-slate-100 mb-4 block"></i>
        <p class="text-xs font-bold text-slate-400 uppercase">Menu tidak ditemukan</p>
    </div>
</main>

@include('customer.components.mini-cart')
@endsection

@section('scripts')
<script>
    // Logic Search Client-side
    document.getElementById('search-input').addEventListener('input', function() {
        var kw = this.value.toLowerCase().trim();
        var foundCount = 0;
        document.querySelectorAll('.menu-card').forEach(function(card) {
            var name = card.dataset.name || '';
            var ok = !kw || name.includes(kw);
            card.style.display = ok ? '' : 'none';
            if (ok) foundCount++;
        });
        document.querySelectorAll('.category-section').forEach(function(sec) {
            var visibleItems = Array.from(sec.querySelectorAll('.menu-card')).some(c => c.style.display !== 'none');
            sec.style.display = visibleItems ? '' : 'none';
        });
        document.getElementById('search-empty').classList.toggle('hidden', foundCount > 0);
    });
</script>
@endsection