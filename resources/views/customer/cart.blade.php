@extends('layouts.customer')
@section('content')
<main class="px-5 mt-6">
    <h2 class="text-xl font-black text-gray-800 uppercase tracking-tighter mb-6">Keranjang Kamu</h2>

    @if(count($cart) > 0)
    <div class="space-y-4 mb-52" id="cart-items">
        @foreach($cart as $id => $item)
        <div class="cart-item-{{ $id }} bg-white p-4 rounded-[2rem] border border-gray-100 shadow-sm flex gap-4 items-center transition-all duration-300">
            <div class="w-20 h-20 bg-gray-50 rounded-2xl overflow-hidden flex-shrink-0">
                <img src="{{ asset('storage/'.$item['image']) }}" class="w-full h-full object-cover"
                     onerror="this.src='https://via.placeholder.com/150/f97316/ffffff?text=WN'">
            </div>
            <div class="flex-1">
                <h4 class="text-xs font-bold text-gray-800">{{ $item['name'] }}</h4>
                <p class="text-orange-600 font-black text-sm mt-1">Rp{{ number_format($item['price']) }}</p>
                <p class="text-[10px] font-bold text-gray-400 mt-1">Qty: <span class="item-qty-{{ $id }}">{{ $item['quantity'] }}</span></p>
                <p class="text-[10px] font-bold text-gray-500 mt-0.5">
                    Subtotal: <span class="font-black text-orange-500">Rp{{ number_format($item['price'] * $item['quantity']) }}</span>
                </p>
            </div>
            {{-- ✅ Tombol hapus yang berfungsi --}}
            <button onclick="removeItem({{ $id }})"
                    class="w-9 h-9 bg-red-50 text-red-400 rounded-xl flex items-center justify-center active:scale-90 transition hover:bg-red-100">
                <i class="fa-solid fa-trash-can text-xs"></i>
            </button>
        </div>
        @endforeach
    </div>

    {{-- SUMMARY + TOMBOL PESAN --}}
    <div class="fixed bottom-20 inset-x-5 z-[90]">
        <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-2xl mb-3">
            <div class="flex justify-between items-center text-[10px] text-gray-400 font-bold mb-2">
                <span class="uppercase">Total Item</span>
                <span id="summary-qty" class="text-gray-700">{{ collect($cart)->sum('quantity') }} item</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-[10px] font-bold text-gray-400 uppercase">Total Bayar</span>
                <span class="text-xl font-black text-orange-600 tracking-tighter" id="summary-total">Rp{{ number_format($total) }}</span>
            </div>
        </div>
        <button onclick="placeOrder()"
                class="w-full bg-[#121826] text-white py-5 rounded-3xl font-black text-sm uppercase tracking-widest shadow-xl active:scale-95 transition">
            Pesan Sekarang 🍽️
        </button>
    </div>

    @else
    {{-- EMPTY STATE --}}
    <div class="py-24 text-center" id="empty-state">
        <i class="fa-solid fa-cart-shopping text-6xl text-gray-100 mb-6 block"></i>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest italic mb-6">Belum ada menu yang dipilih</p>
        <a href="{{ route('customer.menu') }}"
           class="bg-orange-500 text-white px-8 py-3 rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-lg">
            Lihat Menu
        </a>
    </div>
    @endif
</main>
@endsection

@section('scripts')
<script>
// ✅ Hapus item dari keranjang
function removeItem(id) {
    Swal.fire({
        title: 'Hapus item?',
        text: 'Item ini akan dihapus dari keranjang.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-3xl' }
    }).then(function(result) {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ url('/cart/remove') }}/" + id,
                method: "POST",
                success: function(res) {
                    // Animasi keluar lalu hapus dari DOM
                    $('.cart-item-' + id).css({ opacity: 0, transform: 'translateX(100px)' });
                    setTimeout(function() {
                        $('.cart-item-' + id).remove();

                        // Update badge header
                        if (res.cart_count > 0) {
                            $('.cart-count').text(res.cart_count).removeClass('hidden');
                        } else {
                            $('.cart-count').addClass('hidden');
                        }

                        // Cek kalau keranjang sudah kosong
                        if (Object.keys(res.cart).length === 0) {
                            location.reload(); // Reload untuk tampilkan empty state
                        }
                    }, 300);

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1000,
                        icon: 'success',
                        title: 'Item dihapus'
                    });
                }
            });
        }
    });
}

// ✅ Place order
function placeOrder() {
    Swal.fire({
        title: 'Konfirmasi Pesanan',
        text: 'Yakin mau pesan sekarang?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#121826',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Pesan!',
        cancelButtonText: 'Cek dulu',
        customClass: { popup: 'rounded-3xl' }
    }).then(function(result) {
        if (result.isConfirmed) {
            // Tampilkan loading
            Swal.fire({
                title: 'Memproses...',
                allowOutsideClick: false,
                didOpen: function() { Swal.showLoading(); }
            });

            $.ajax({
                url: "{{ route('customer.order') }}",
                method: "POST",
                success: function(res) {
                    Swal.fire({
                        title: 'Pesanan Masuk! 🎉',
                        text: 'Pesananmu sedang diproses oleh dapur.',
                        icon: 'success',
                        confirmButtonColor: '#f97316',
                        confirmButtonText: 'Oke, siap!',
                        customClass: { popup: 'rounded-3xl' }
                    }).then(function() {
                        window.location.href = "{{ route('customer.home') }}";
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Gagal!',
                        text: xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan.',
                        icon: 'error',
                        confirmButtonColor: '#f97316'
                    });
                }
            });
        }
    });
}
</script>
@endsection