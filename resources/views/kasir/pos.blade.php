@extends('layouts.kasir')

@section('title', 'POS Take Away')

@push('head')
    {{-- SDK Midtrans Snap --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
@endpush

@section('content')
<div x-data="posSystem()" class="flex flex-col lg:flex-row gap-8 items-start min-h-screen">
    
    <div class="w-full lg:w-2/3">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Menu <span class="text-orange-500">Nusantara</span></h2>
            <div class="relative w-64">
                <input type="text" placeholder="Cari menu..." class="w-full bg-white border border-gray-200 py-2 px-4 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 outline-none">
            </div>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <template x-for="menu in menus" :key="menu.id">
                <button @click="addToCart(menu)" class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm hover:border-orange-500 hover:shadow-orange-500/10 transition-all text-left group relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-orange-500 text-white px-3 py-1 rounded-bl-2xl text-[10px] font-black opacity-0 group-hover:opacity-100 transition">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <p class="font-bold text-slate-700 leading-tight mb-2" x-text="menu.name"></p>
                    <p class="text-orange-600 font-black" x-text="formatRupiah(menu.price)"></p>
                </button>
            </template>
        </div>
    </div>

    <div class="w-full lg:w-1/3 bg-white p-6 rounded-[2.5rem] border border-gray-200 shadow-xl sticky top-24">
        <div class="flex items-center gap-3 mb-6 border-b border-dashed border-gray-100 pb-4">
            <div class="w-10 h-10 bg-orange-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-orange-500/30">
                <i class="fa-solid fa-shopping-basket"></i>
            </div>
            <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Checkout <span class="text-orange-500">POS</span></h3>
        </div>
        
        <div class="space-y-3 mb-6 max-h-60 overflow-y-auto pr-2">
            <template x-show="cart.length === 0">
                <div class="text-center py-10 text-slate-400">
                    <p class="text-sm italic">Keranjang kosong</p>
                </div>
            </template>
            <template x-for="(item, index) in cart" :key="item.id">
                <div class="flex justify-between items-center bg-slate-50 p-4 rounded-2xl border border-gray-100">
                    <div class="max-w-[150px]">
                        <p class="font-bold text-slate-700 text-sm truncate" x-text="item.name"></p>
                        <p class="text-[10px] text-slate-400" x-text="formatRupiah(item.price)"></p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center bg-white border border-gray-200 rounded-lg shadow-sm">
                            <button @click="decrease(index)" class="px-2 py-1 text-slate-400 hover:text-orange-500"><i class="fa-solid fa-minus text-[10px]"></i></button>
                            <span class="px-2 font-black text-xs text-slate-700" x-text="item.quantity"></span>
                            <button @click="increase(index)" class="px-2 py-1 text-slate-400 hover:text-orange-500"><i class="fa-solid fa-plus text-[10px]"></i></button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="cart.length > 0">
            <div class="space-y-4">
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Pelanggan</label>
                    <input type="text" x-model="customerName" placeholder="Contoh: Budi" class="w-full border border-gray-200 p-3.5 rounded-2xl mt-1 font-bold text-slate-700 focus:ring-2 focus:ring-orange-500 outline-none transition">
                </div>

                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Metode Bayar</label>
                    <select x-model="paymentMethod" class="w-full border border-gray-200 p-3.5 rounded-2xl mt-1 font-bold text-slate-700 outline-none focus:ring-2 focus:ring-orange-500 transition">
                        <option value="cash">💵 Tunai (Cash)</option>
                        <option value="midtrans">📱 Midtrans / QRIS</option>
                    </select>
                </div>

                <div x-show="paymentMethod === 'cash'" x-transition class="bg-orange-50 p-5 rounded-3xl border border-orange-100 space-y-3">
                    <div>
                        <label class="text-[10px] font-black text-orange-400 uppercase tracking-widest">Nominal Uang</label>
                        <input type="number" x-model.number="cashReceived" placeholder="0" class="w-full bg-white border border-orange-200 p-3.5 rounded-2xl mt-1 font-black text-2xl text-orange-600 outline-none shadow-inner">
                    </div>
                    <div class="flex justify-between items-center px-1">
                        <span class="text-[10px] font-black text-orange-400 uppercase">Kembalian</span>
                        <span class="text-xl font-black text-slate-800" :class="calculateChange() < 0 ? 'text-red-500' : ''" x-text="formatRupiah(calculateChange())"></span>
                    </div>
                </div>
            </div>

            <div class="my-6 pt-6 border-t border-dashed border-gray-200">
                <div class="flex justify-between items-end">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Bayar</span>
                    <span class="text-3xl font-black text-orange-600 tracking-tighter" x-text="formatRupiah(totalPrice())"></span>
                </div>
            </div>

            <button @click="processTransaction()" :disabled="loading || (paymentMethod === 'cash' && (cashReceived < totalPrice() || !cashReceived))" class="w-full bg-slate-900 hover:bg-black text-white py-5 rounded-3xl font-black uppercase tracking-widest shadow-xl shadow-slate-900/20 transition-all active:scale-95 flex items-center justify-center gap-3 disabled:opacity-30">
                <template x-if="!loading">
                    <span><i class="fa-solid fa-cash-register"></i> Proses Pesanan</span>
                </template>
                <template x-if="loading">
                    <span><i class="fa-solid fa-spinner fa-spin"></i> Memproses...</span>
                </template>
            </button>
        </div>
    </div>
</div>

<script>
    function posSystem() {
        return {
            menus: @json($menus),
            cart: [],
            customerName: '',
            paymentMethod: 'cash',
            cashReceived: null,
            loading: false,

            addToCart(menu) {
                let item = this.cart.find(i => i.id === menu.id);
                if (item) item.quantity++;
                else this.cart.push({...menu, quantity: 1});
            },

            increase(index) { this.cart[index].quantity++; },
            decrease(index) {
                if (this.cart[index].quantity > 1) this.cart[index].quantity--;
                else this.cart.splice(index, 1);
            },

            totalPrice() { return this.cart.reduce((t, i) => t + (i.price * i.quantity), 0); },
            calculateChange() { return (this.cashReceived || 0) - this.totalPrice(); },

            async processTransaction() {
                if (!this.customerName) return alert("Nama pelanggan wajib diisi!");
                this.loading = true;

                let formData = new FormData();
                // Pastikan lu udah naruh tag <meta name="csrf-token" content="{{ csrf_token() }}"> di file layouts/kasir.blade.php lu ya
                formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');
                formData.append('customer_name', this.customerName);
                formData.append('cart', JSON.stringify(this.cart));
                formData.append('payment_method', this.paymentMethod);
                
                // 🔥 FIX: Cuma kirim cash_received kalau beneran ada angkanya
                if (this.paymentMethod === 'cash' && this.cashReceived !== null) {
                    formData.append('cash_received', this.cashReceived);
                }

                try {
                    let response = await fetch("{{ route('kasir.pos.store') }}", { 
                        method: 'POST', 
                        headers: {
                            'Accept': 'application/json' // 🔥 FIX: Paksa Laravel jawab pakai JSON, bukan HTML
                        },
                        body: formData 
                    });
                    
                    let result = await response.json();

                    if (!response.ok) {
                        // Kalau Laravel ngasih error validasi atau server 500, kita tangkap pesannya
                        console.error("Server Error:", result);
                        alert("Gagal: " + (result.message || "Terjadi kesalahan di server. Cek console!"));
                        this.loading = false;
                        return;
                    }

                    if (result.status === 'midtrans') {
                        window.snap.pay(result.snap_token, {
                            onSuccess: () => { window.location.href = "{{ route('kasir.dashboard') }}"; },
                            onPending: () => { window.location.href = "{{ route('kasir.dashboard') }}"; },
                            onError: () => { alert("Pembayaran Gagal atau Dibatalkan!"); this.loading = false; },
                            onClose: () => { alert("Anda menutup popup sebelum membayar."); this.loading = false; }
                        });
                    } else if (result.status === 'cash') {
                        window.location.href = "{{ route('kasir.dashboard') }}";
                    } else {
                        alert(result.message);
                        this.loading = false;
                    }
                } catch (err) {
                    // Kalau error-nya parah (misal Midtrans SDK gagal load atau koneksi putus)
                    console.error("Fetch Error:", err);
                    alert("Gagal menghubungi server. Cek console (F12) untuk detailnya.");
                    this.loading = false;
                }
            },
        }
    }
</script>
@endsection