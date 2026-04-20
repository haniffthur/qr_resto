@extends('layouts.customer')
@section('content')
<main class="px-5 py-4">

    <div class="sticky top-[72px] bg-white/95 backdrop-blur-md z-[90] pb-4">
        <input type="text" id="search-input" placeholder="Cari menu favorit..."
               class="w-full px-5 py-3.5 bg-gray-100 border-none rounded-[1.5rem] text-xs outline-none focus:ring-2 focus:ring-orange-500 transition-all">
    </div>

    @foreach($categories as $category)
    <section class="mb-10 category-section" data-category="{{ $category->id }}">
        <h3 class="font-black text-gray-800 text-sm uppercase tracking-widest mb-5 border-l-4 border-orange-500 pl-3">
            {{ $category->name }}
        </h3>
        <div class="grid grid-cols-2 gap-4">
            @foreach($category->menus as $menu)
            <div class="menu-card bg-white rounded-[2rem] p-3 border border-gray-100 shadow-sm"
                 data-name="{{ strtolower($menu->name) }}">
                <a href="{{ route('customer.menu.detail', $menu->id) }}"
                   class="block relative aspect-square rounded-[1.5rem] overflow-hidden mb-3">
                    <img src="{{ asset('storage/'.$menu->image) }}" class="w-full h-full object-cover"
                         onerror="this.src='https://placehold.co/200x200/f97316/ffffff?text=WN'">
                    <div class="absolute bottom-2 right-2 bg-white/90 px-2 py-1 rounded-lg text-[9px] font-black text-orange-500">⭐ 4.8</div>
                </a>
                <h4 class="text-[11px] font-bold text-gray-800 line-clamp-1 mb-3">{{ $menu->name }}</h4>
                <div class="flex justify-between items-center">
                    <span class="text-orange-600 font-black text-xs">Rp{{ number_format($menu->price) }}</span>
                    <button onclick="doAddToCart({{ $menu->id }})"
                            class="w-8 h-8 bg-[#121826] text-white rounded-xl flex items-center justify-center active:scale-90 transition shadow-lg">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endforeach

    <div id="search-empty" class="hidden py-20 text-center pb-32">
        <i class="fa-solid fa-magnifying-glass text-5xl text-gray-100 mb-4 block"></i>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Menu tidak ditemukan</p>
    </div>
</main>

{{-- MINI CART --}}
<div id="mini-cart" class="hidden fixed bottom-24 inset-x-5 z-[110] transform translate-y-full transition-all duration-500 ease-in-out">
    <div class="bg-[#121826] rounded-3xl p-4 shadow-2xl flex items-center justify-between border border-white/10">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 bg-orange-500 rounded-xl flex items-center justify-center text-white relative shadow-lg">
                <i class="fa-solid fa-bag-shopping"></i>
                <span id="cart-qty" class="absolute -top-2 -right-2 bg-white text-[#121826] text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center border-2 border-[#121826]">0</span>
            </div>
            <div class="text-white">
                <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">Total Pesanan</p>
                <p class="text-sm font-black">Rp <span id="cart-total-text">0</span></p>
            </div>
        </div>
        <a href="{{ route('customer.cart') }}"
           class="bg-orange-500 text-white px-5 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest active:scale-95 transition shadow-lg">
            Cek Keranjang
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
var CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function showMiniCart(qty, total) {
    document.getElementById('cart-qty').textContent = qty;
    document.getElementById('cart-total-text').textContent = total;
    document.querySelectorAll('.cart-count').forEach(function(b){ b.textContent=qty; b.classList.remove('hidden'); });
    var mc = document.getElementById('mini-cart');
    mc.classList.remove('hidden');
    setTimeout(function(){ mc.classList.remove('translate-y-full'); mc.classList.add('translate-y-0'); }, 10);
}

function doAddToCart(menuId) {
    // ✅ URL RELATIF — tidak hardcode domain, aman untuk ngrok/production
    var formData = new FormData();
    formData.append('menu_id', menuId);
    formData.append('_token', CSRF);

    fetch('/cart/add', {
        method : 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body   : formData
    })
    .then(function(r) {
        if (!r.ok) return r.text().then(function(t){ throw new Error('HTTP ' + r.status + ' — ' + t.slice(0, 400)); });
        return r.json();
    })
    .then(function(res) {
        showMiniCart(res.cart_count, res.total_price);
        Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:1500, timerProgressBar:true })
            .fire({ icon:'success', title:'Ditambahkan ke keranjang!' });
    })
    .catch(function(err) {
        console.error('[cart/add]', err.message);
        Swal.fire({ title:'Gagal!', html:'<small style="word-break:break-all">'+err.message+'</small>', icon:'error', confirmButtonColor:'#f97316' });
    });
}

// Search
document.getElementById('search-input').addEventListener('input', function() {
    var kw = this.value.toLowerCase().trim();
    var n  = 0;
    document.querySelectorAll('.menu-card').forEach(function(c) {
        var ok = !kw || (c.dataset.name||'').includes(kw);
        c.style.display = ok ? '' : 'none';
        if (ok) n++;
    });
    document.querySelectorAll('.category-section').forEach(function(s) {
        var vis = Array.from(s.querySelectorAll('.menu-card')).some(function(c){ return c.style.display!=='none'; });
        s.style.display = vis ? '' : 'none';
    });
    document.getElementById('search-empty').classList.toggle('hidden', n > 0);
});

// Init mini cart dari session
(function(){
    @php $sq=0;$st=0; foreach(session('cart',[]) as $i){$sq+=$i['quantity'];$st+=$i['price']*$i['quantity'];} @endphp
    @if($sq > 0) showMiniCart({{ $sq }}, '{{ number_format($st,0,",",".") }}'); @endif
})();
</script>
@endsection