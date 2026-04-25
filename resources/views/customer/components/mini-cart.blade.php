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