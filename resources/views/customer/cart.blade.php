@extends('layouts.customer')

@section('content')
<main class="px-5 mt-6 pb-60">
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('customer.menu') }}" class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-500">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        <h2 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Keranjang Kamu 🛒</h2>
    </div>

    @if(count($cart) > 0)
    <div class="space-y-4" id="cart-wrapper">
        @foreach($cart as $id => $item)
        <div class="cart-item-{{ $id }} bg-white p-4 rounded-[2rem] border border-slate-50 shadow-sm flex gap-4 items-center transition-all duration-500">
            <div class="w-20 h-20 bg-slate-50 rounded-2xl overflow-hidden shrink-0 border border-slate-100">
                <img src="{{ asset('storage/'.$item['image']) }}" class="w-full h-full object-cover"
                     onerror="this.src='https://via.placeholder.com/150/f97316/ffffff?text=WN'">
            </div>
            <div class="flex-1">
                <h4 class="text-xs font-black text-slate-800 leading-tight">{{ $item['name'] }}</h4>
                <p class="text-orange-600 font-black text-sm mt-1">Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-2 py-0.5 rounded-md border border-slate-100">
                        Qty: {{ $item['quantity'] }}
                    </span>
                </div>
            </div>
            
            {{-- Tombol Hapus --}}
            <button onclick="removeItem({{ $id }})"
                    class="w-10 h-10 bg-red-50 text-red-400 rounded-xl flex items-center justify-center active:scale-90 transition hover:bg-red-500 hover:text-white">
                <i class="fa-solid fa-trash-can text-xs"></i>
            </button>
        </div>
        @endforeach
    </div>

    {{-- SUMMARY & CHECKOUT --}}
    <div class="fixed bottom-24 inset-x-5 z-[90]">
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-2xl mb-4 animate__animated animate__slideInUp">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Bayar</p>
                    <p class="text-xl font-black text-orange-600 tracking-tighter" id="summary-total">
                        Rp{{ number_format($total, 0, ',', '.') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Meja</p>
                    <p class="text-sm font-black text-slate-800">{{ session('table_number', '-') }}</p>
                </div>
            </div>
            
            <button onclick="placeOrder()" 
                    class="w-full bg-slate-900 text-white py-5 rounded-3xl font-black text-sm uppercase tracking-[0.2em] shadow-xl shadow-slate-200 active:scale-95 transition flex items-center justify-center gap-3">
                <span>Konfirmasi Pesanan</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </div>
    </div>

    @else
    {{-- EMPTY STATE --}}
    <div class="py-24 text-center">
        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-slate-100">
            <i class="fa-solid fa-cart-shopping text-3xl text-slate-200"></i>
        </div>
        <p class="text-xs font-black text-slate-400 uppercase tracking-widest italic mb-8">Belum ada menu yang dipilih, Nif.</p>
        <a href="{{ route('customer.menu') }}"
           class="inline-block bg-orange-500 text-white px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-orange-200 active:scale-95 transition">
            Pilih Menu Sekarang
        </a>
    </div>
    @endif
</main>
@endsection

@section('scripts')
<script>
// ✅ Fungsi Hapus Item (Pake jQuery biar sinkron ama layout)
function removeItem(id) {
    Swal.fire({
        title: 'Hapus Menu?',
        text: "Menu ini akan dikeluarkan dari keranjang.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#cbd5e1',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-[2rem]' }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/cart/remove/" + id,
                method: "POST",
                success: function(res) {
                    // Animasi Hapus
                    $('.cart-item-' + id).addClass('opacity-0 -translate-x-full');
                    setTimeout(() => {
                        $('.cart-item-' + id).remove();
                        
                        // Update total harga di summary
                        $('#summary-total').text('Rp' + res.total_price);
                        
                        // Update badge di header layout
                        if(res.cart_count > 0) {
                            $('.cart-count').text(res.cart_count);
                        } else {
                            location.reload(); // Reload kalo kosong buat nampilinn empty state
                        }
                    }, 500);

                    Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Dihapus!', showConfirmButton:false, timer:1000 });
                }
            });
        }
    });
}

// ✅ Fungsi Checkout Midtrans
function placeOrder() {
    Swal.fire({
        title: 'Siap Pesan?',
        text: "Pesanan akan langsung dikirim ke dapur setelah pembayaran lunas.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#121826',
        confirmButtonText: 'Bayar Sekarang',
        cancelButtonText: 'Nanti Dulu',
        customClass: { popup: 'rounded-[2rem]' }
    }).then((result) => {
        if (result.isConfirmed) {
            // Tampilkan Loading
            Swal.fire({ title: 'Memproses Pesanan...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

            $.ajax({
                url: "{{ route('customer.order') }}",
                method: "POST",
                success: function(res) {
                    Swal.close();
                    
                    // PANGGIL POPUP MIDTRANS
                    window.snap.pay(res.snap_token, {
                        onSuccess: function(result) {
                            Swal.fire({ icon: 'success', title: 'Pembayaran Berhasil!', text: 'Pesanan lu sedang dimasak, Nif!', customClass: { popup: 'rounded-[2rem]' }})
                            .then(() => { window.location.href = "{{ route('customer.home') }}"; });
                        },
                        onPending: function(result) {
                            Swal.fire({ icon: 'info', title: 'Menunggu Pembayaran', text: 'Selesaikan pembayaran lu biar pesanan masuk dapur.', customClass: { popup: 'rounded-[2rem]' }})
                            .then(() => { window.location.href = "{{ route('customer.home') }}"; });
                        },
                        onError: function(result) {
                            Swal.fire('Gagal!', 'Pembayaran lu bermasalah, coba lagi ya.', 'error');
                        }
                    });
                },
                error: function(xhr) {
                    Swal.fire('Waduh!', xhr.responseJSON.message, 'error');
                }
            });
        }
    });
}
</script>
@endsection