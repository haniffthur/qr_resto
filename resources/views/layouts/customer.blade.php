<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Warung Nusantara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-white pb-28">

    <header class="bg-white px-5 py-4 flex justify-between items-center sticky top-0 z-[100] shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center text-white shadow-lg font-bold">WN</div>
            <div>
                <h1 class="font-extrabold text-sm text-gray-800 leading-none uppercase">Warung Nusantara</h1>
                <p class="text-[10px] text-gray-400 mt-1 uppercase">Cita Rasa Asli Indonesia</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('customer.cart') }}" class="w-10 h-10 bg-orange-50 rounded-full flex items-center justify-center text-orange-500 relative">
                <i class="fa-solid fa-bag-shopping text-sm"></i>
                @php
                    $cartCount = collect(session('cart', []))->sum('quantity');
                @endphp
                <span class="cart-count absolute -top-1 -right-1 w-5 h-5 bg-orange-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-white {{ $cartCount > 0 ? '' : 'hidden' }}">
                    {{ $cartCount }}
                </span>
            </a>
            <div class="w-10 h-10 bg-[#121826] rounded-full flex items-center justify-center text-white">
                <i class="fa-solid fa-bars-staggered text-sm"></i>
            </div>
        </div>
    </header>

    @yield('content')

    <nav class="fixed bottom-0 inset-x-0 bg-white border-t border-gray-100 px-6 py-3 flex justify-between items-end z-[100]">
        <a href="{{ route('customer.home') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('customer.home') ? 'text-orange-500' : 'text-gray-400' }}">
            <i class="fa-solid fa-house text-lg"></i>
            <span class="text-[10px] font-bold">Beranda</span>
        </a>
        <a href="{{ route('customer.menu') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('customer.menu') ? 'text-orange-500' : 'text-gray-400' }}">
            <i class="fa-solid fa-utensils text-lg"></i>
            <span class="text-[10px] font-bold">Menu</span>
        </a>
        <div class="relative -top-5">
            <button class="w-16 h-16 bg-[#121826] text-white rounded-full flex items-center justify-center shadow-xl border-[4px] border-white active:scale-95 transition">
                <i class="fa-solid fa-qrcode text-2xl"></i>
            </button>
            <span class="block text-center text-[9px] font-bold text-gray-400 mt-1 uppercase">Scan</span>
        </div>
        <a href="{{ route('customer.cart') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('customer.cart') ? 'text-orange-500' : 'text-gray-400' }}">
            <i class="fa-solid fa-clipboard-list text-lg"></i>
            <span class="text-[10px] font-bold">Pesanan</span>
        </a>
        <a href="{{ route('customer.contact') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('customer.contact') ? 'text-orange-500' : 'text-gray-400' }}">
    <i class="fa-solid fa-headset text-lg"></i>
    <span class="text-[10px] font-bold">Kontak</span>
</a>
    </nav>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        // ✅ SATU fungsi addToCart global, tidak ada duplikat
        function addToCart(id) {
            $.ajax({
                url: "{{ url('/cart/add') }}",
                method: "POST",
                data: { menu_id: id },
                success: function(res) {
                    // Update badge icon keranjang di header
                    var count = res.cart_count;
                    $('.cart-count').text(count).removeClass('hidden');

                    // Update mini-cart
                    $('#cart-qty').text(count);
                    $('#cart-total-text').text(res.total_price);

                    // Tampilkan mini-cart dengan animasi
                    $('#mini-cart').removeClass('hidden');
                    setTimeout(function() {
                        $('#mini-cart').removeClass('translate-y-full').addClass('translate-y-0');
                    }, 10);

                    // Toast sukses
                    Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1200,
                        timerProgressBar: true
                    }).fire({ icon: 'success', title: 'Ditambahkan ke keranjang!' });
                },
                error: function(xhr) {
                    var msg = 'Gagal menambahkan menu.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        title: 'Oops!',
                        text: msg,
                        icon: 'error',
                        confirmButtonColor: '#f97316',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        // Inisialisasi mini-cart saat halaman load
        $(document).ready(function() {
            @php
                $cartQty   = 0;
                $cartTotal = 0;
                foreach(session('cart', []) as $item) {
                    $cartQty   += $item['quantity'];
                    $cartTotal += $item['price'] * $item['quantity'];
                }
            @endphp

            @if($cartQty > 0)
                $('#cart-qty').text('{{ $cartQty }}');
                $('#cart-total-text').text('{{ number_format($cartTotal, 0, ",", ".") }}');
                $('#mini-cart').removeClass('hidden');
                setTimeout(function() {
                    $('#mini-cart').removeClass('translate-y-full').addClass('translate-y-0');
                }, 10);
            @endif
        });
    </script>

    @yield('scripts')
</body>
</html>