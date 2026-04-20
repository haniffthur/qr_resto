@extends('layouts.customer')
@section('content')
<main class="px-5 mt-2 space-y-6">

    {{-- HERO --}}
    <div class="relative rounded-[2rem] overflow-hidden bg-gray-900 h-48 shadow-lg">
        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800"
             class="absolute inset-0 w-full h-full object-cover opacity-60">
        <div class="absolute inset-0 p-6 flex flex-col justify-center">
            <p class="text-orange-400 text-[10px] font-bold uppercase tracking-widest mb-1">Selamat Datang</p>
            <h2 class="text-2xl font-black text-white leading-tight">Pesan Makanan<br>Langsung di Meja!</h2>
            <p class="text-white/70 text-[10px] mt-2 mb-4">Nikmati kemudahan memesan dari tempat duduk Anda</p>
            <a href="{{ route('customer.menu') }}"
               class="bg-orange-500 w-fit px-6 py-2 rounded-xl text-[11px] font-bold text-white uppercase tracking-wider shadow-lg">
                Mulai Pesan
            </a>
        </div>
    </div>

    {{-- INFO MEJA --}}
    <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white">
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <div>
                <h4 class="text-emerald-900 text-xs font-bold uppercase">Meja {{ session('table_number', '—') }}</h4>
                <p class="text-emerald-600 text-[10px] font-medium">Siap untuk memesan!</p>
            </div>
        </div>
        <a href="{{ route('customer.menu') }}" class="bg-emerald-500 text-white px-4 py-2 rounded-xl text-[10px] font-bold">
            Pesan Sekarang
        </a>
    </div>

    {{-- PROMO --}}
    <section>
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-extrabold text-gray-800 text-sm">Promo Hari Ini</h3>
            <span class="text-orange-500 text-[10px] font-bold uppercase">Lihat Semua</span>
        </div>
        <div class="flex overflow-x-auto gap-4 no-scrollbar">
            <div class="min-w-[280px] h-36 relative rounded-[1.5rem] overflow-hidden shadow-sm flex-shrink-0">
                <img src="https://images.unsplash.com/photo-1544145945-f904253d0c7b?auto=format&fit=crop&w=600"
                     class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/40 p-5 flex flex-col justify-end text-white">
                    <p class="text-[9px] font-bold opacity-80 italic">14.00 - 16.00</p>
                    <h4 class="text-lg font-black leading-tight">Happy Hour</h4>
                    <span class="bg-orange-500 w-fit px-3 py-1 rounded-full text-[9px] font-bold mt-2 uppercase">30% OFF</span>
                </div>
            </div>
        </div>
    </section>

    {{-- KATEGORI --}}
    <section>
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-extrabold text-gray-800 text-sm">Kategori</h3>
            <a href="{{ route('customer.menu') }}" class="text-orange-500 text-[10px] font-bold uppercase">Lihat Menu</a>
        </div>
        <div class="grid grid-cols-4 gap-3">
            <button onclick="filterCat('all')" data-cat="all"
                    class="cat-btn flex flex-col items-center gap-2 p-2 rounded-2xl border-2 border-orange-500 bg-orange-50 active:scale-95 transition">
                <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-border-all text-white text-sm"></i>
                </div>
                <p class="text-[9px] font-black text-orange-500 uppercase">Semua</p>
            </button>
            @foreach($categories as $cat)
            <button onclick="filterCat('{{ $cat->id }}')" data-cat="{{ $cat->id }}"
                    class="cat-btn flex flex-col items-center gap-2 p-2 rounded-2xl border-2 border-gray-100 bg-white active:scale-95 transition">
                <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center overflow-hidden">
                    @if($cat->image ?? null)
                        <img src="{{ asset('storage/'.$cat->image) }}" class="w-full h-full object-cover">
                    @else
                        <i class="fa-solid fa-utensils text-gray-400 text-sm"></i>
                    @endif
                </div>
                <p class="text-[9px] font-bold text-gray-700 uppercase line-clamp-1">{{ $cat->name }}</p>
            </button>
            @endforeach
        </div>
    </section>

    {{-- MENU POPULER --}}
    <section>
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-extrabold text-gray-800 text-sm" id="menu-title">Menu Populer</h3>
            <a href="{{ route('customer.menu') }}" class="text-orange-500 text-[10px] font-bold uppercase">Lihat Semua</a>
        </div>

        <div class="grid grid-cols-1 space-y-4 pb-4" id="menu-list">
            @forelse($popularMenus as $menu)
            <div class="menu-card bg-white rounded-2xl border border-gray-100 shadow-sm p-3 flex gap-4"
                 data-category="{{ $menu->category_id }}">
                <div class="w-24 h-24 rounded-xl overflow-hidden flex-shrink-0">
                    <img src="{{ asset('storage/'.$menu->image) }}" class="w-full h-full object-cover"
                         onerror="this.src='https://placehold.co/150x150/f97316/ffffff?text=WN'">
                </div>
                <div class="flex flex-col justify-between flex-1 py-1">
                    <div>
                        <span class="text-[8px] font-bold text-gray-400 bg-gray-50 px-2 py-0.5 rounded-full uppercase">
                            {{ $menu->category->name ?? '-' }}
                        </span>
                        <h4 class="text-xs font-bold text-gray-800 mt-1">{{ $menu->name }}</h4>
                        <p class="text-[10px] text-orange-400 font-bold mt-0.5">⭐ 4.8</p>
                        <p class="text-orange-600 font-black text-sm mt-1">Rp {{ number_format($menu->price) }}</p>
                    </div>
                    <button onclick="doAddToCart({{ $menu->id }})"
                            class="bg-orange-500 text-white w-full py-2 rounded-lg text-[9px] font-black uppercase tracking-widest active:scale-95 transition">
                        + Tambah
                    </button>
                </div>
            </div>
            @empty
            <div class="py-10 text-center">
                <i class="fa-solid fa-plate-wheat text-5xl text-gray-100 mb-3 block"></i>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Belum ada menu populer</p>
                <a href="{{ route('customer.menu') }}"
                   class="inline-block mt-4 bg-orange-500 text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase">
                    Lihat Semua Menu
                </a>
            </div>
            @endforelse
        </div>

        <div id="menu-empty" class="hidden py-10 text-center">
            <i class="fa-solid fa-plate-wheat text-5xl text-gray-100 mb-3 block"></i>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Belum ada menu di kategori ini</p>
        </div>
    </section>

    {{-- FOOTER --}}
    <div class="bg-gray-50 rounded-[2rem] p-7 mb-10 border border-gray-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center font-black shadow-sm">WN</div>
            <div>
                <h5 class="text-xs font-black text-gray-800 uppercase">Warung Nusantara</h5>
                <p class="text-[9px] text-gray-400 italic font-bold">Cita Rasa Asli Indonesia</p>
            </div>
        </div>
        <div class="space-y-3 text-[10px] text-gray-500 font-bold">
            <div class="flex gap-3"><i class="fa-solid fa-location-dot text-orange-500"></i> Jl. Sudirman No. 88, Jakarta Selatan</div>
            <div class="flex gap-3"><i class="fa-solid fa-clock text-orange-500"></i> Senin-Minggu: 10.00-22.00 WIB</div>
            <div class="flex gap-3"><i class="fa-solid fa-phone text-orange-500"></i> +62 812-3456-7890</div>
        </div>
        <button class="w-full mt-6 bg-[#121826] text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest">
            Hubungi Kami
        </button>
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

// ✅ URL relatif /cart/add — aman untuk ngrok, localhost, maupun production
function doAddToCart(menuId) {
    var fd = new FormData();
    fd.append('menu_id', menuId);
    fd.append('_token', CSRF);

    fetch('/cart/add', {
        method : 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body   : fd
    })
    .then(function(r) {
        if (!r.ok) return r.text().then(function(t){ throw new Error('HTTP '+r.status+' — '+t.slice(0,400)); });
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

function filterCat(catId) {
    document.querySelectorAll('.cat-btn').forEach(function(btn) {
        var on = btn.dataset.cat == catId;
        btn.classList.toggle('border-orange-500', on); btn.classList.toggle('bg-orange-50', on);
        btn.classList.toggle('border-gray-100', !on);  btn.classList.toggle('bg-white', !on);
        var p = btn.querySelector('p');
        if (p) { p.classList.toggle('text-orange-500', on); p.classList.toggle('text-gray-700', !on); }
    });
    var cards=document.querySelectorAll('.menu-card'), vis=0;
    cards.forEach(function(c){ var ok=catId==='all'||c.dataset.category==catId; c.style.display=ok?'':'none'; if(ok)vis++; });
    document.getElementById('menu-title').textContent = catId==='all' ? 'Menu Populer' : 'Menu Pilihan';
    document.getElementById('menu-empty').classList.toggle('hidden', vis>0);
    document.getElementById('menu-list').classList.toggle('hidden', vis===0);
}

(function(){
    @php $sq=0;$st=0; foreach(session('cart',[]) as $i){$sq+=$i['quantity'];$st+=$i['price']*$i['quantity'];} @endphp
    @if($sq > 0) showMiniCart({{ $sq }}, '{{ number_format($st,0,",",".") }}'); @endif
})();
</script>
@endsection