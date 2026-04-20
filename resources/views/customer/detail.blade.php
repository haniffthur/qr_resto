@extends('layouts.customer')
@section('title', $menu->name)

@section('content')
<div class="relative min-h-screen bg-white pb-32 animate__animated animate__fadeIn">
    <div class="relative h-[45vh]">
        <img src="{{ $menu->image ? asset('storage/'.$menu->image) : 'https://via.placeholder.com/800x600?text='.$menu->name }}" 
             class="w-full h-full object-cover">
        
        <a href="{{ route('customer.menu') }}" class="absolute top-6 left-6 w-10 h-10 bg-white/90 backdrop-blur rounded-xl flex items-center justify-center shadow-lg">
            <i class="fa-solid fa-arrow-left text-gray-800"></i>
        </a>

        @if($menu->is_popular)
        <div class="absolute bottom-6 left-6">
            <span class="bg-orange-500 text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest flex items-center gap-2 shadow-lg">
                <i class="fa-solid fa-fire text-xs"></i> Populer
            </span>
        </div>
        @endif
    </div>

    <div class="px-6 -mt-8 bg-white rounded-t-[3rem] pt-8 relative z-10 shadow-[0_-20px_40px_rgba(0,0,0,0.05)]">
        <div class="flex justify-between items-start mb-2">
            <div>
                <span class="text-orange-500 text-[10px] font-black uppercase tracking-widest bg-orange-50 px-3 py-1 rounded-lg">
                    {{ $menu->category->name }}
                </span>
                <h1 class="text-2xl font-black text-gray-900 mt-2 tracking-tight">{{ $menu->name }}</h1>
            </div>
            <div class="text-right">
                <div class="text-sm font-black text-gray-800">
                    ⭐ 4.8 <span class="text-gray-300 font-normal">(99+)</span>
                </div>
            </div>
        </div>

        <h2 class="text-xl font-black text-orange-600 mb-6 tracking-tighter">
            Rp {{ number_format($menu->price, 0, ',', '.') }}
        </h2>

        <div class="grid grid-cols-3 gap-3 mb-8">
            <div class="bg-gray-50 p-3 rounded-2xl text-center">
                <i class="fa-solid fa-clock text-orange-400 mb-1"></i>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">Waktu</p>
                <p class="text-[11px] font-black text-gray-800">{{ $menu->estimated_time ?? '15' }} Mnt</p>
            </div>
            <div class="bg-gray-50 p-3 rounded-2xl text-center text-orange-400">
                <i class="fa-solid fa-utensils mb-1"></i>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">Porsi</p>
                <p class="text-[11px] font-black text-gray-800">Kenyam</p>
            </div>
            <div class="bg-gray-50 p-3 rounded-2xl text-center text-green-400">
                <i class="fa-solid fa-leaf mb-1"></i>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">Status</p>
                <p class="text-[11px] font-black text-gray-800">Fresh</p>
            </div>
        </div>

        <div class="mb-8">
            <h3 class="font-black text-gray-800 mb-2 uppercase text-[10px] tracking-widest">Deskripsi</h3>
            <p class="text-xs text-gray-400 leading-relaxed">
                {{ $menu->description ?? 'Tidak ada deskripsi untuk menu ini.' }}
            </p>
        </div>

        <div class="mb-8">
            <h3 class="font-black text-gray-800 mb-2 uppercase text-[10px] tracking-widest">Catatan Pesanan</h3>
            <textarea id="notes" name="notes" placeholder="Contoh: Tidak pakai sambal, tingkat kepedasan, dll..." 
                class="w-full p-4 bg-gray-50 rounded-2xl border-none text-xs outline-none focus:ring-2 focus:ring-orange-500 min-h-[100px]"></textarea>
        </div>
    </div>
</div>

<div class="fixed bottom-0 inset-x-0 bg-white/80 backdrop-blur-lg px-6 py-6 border-t border-gray-100 z-50">
    <div class="flex items-center gap-4">
        <div class="flex items-center bg-gray-100 rounded-2xl p-1">
            <button onclick="decrementQty()" class="w-10 h-10 flex items-center justify-center text-gray-500 active:scale-90 transition">-</button>
            <span id="qty-display" class="w-8 text-center font-black text-sm">1</span>
            <button onclick="incrementQty()" class="w-10 h-10 bg-orange-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-orange-100 active:scale-90 transition">+</button>
        </div>
        
        <button onclick="submitToCart({{ $menu->id }})" class="flex-1 bg-[#121826] text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-gray-400 active:scale-95 transition">
            Tambah ke Keranjang
        </button>
    </div>
</div>

<script>
    let quantity = 1;
    const price = {{ $menu->price }};

    function incrementQty() {
        quantity++;
        updateDisplay();
    }

    function decrementQty() {
        if (quantity > 1) {
            quantity--;
            updateDisplay();
        }
    }

    function updateDisplay() {
        document.getElementById('qty-display').innerText = quantity;
    }

    function submitToCart(menuId) {
        const notes = document.getElementById('notes').value;
        
        $.ajax({
            url: "{{ route('cart.add') }}",
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                menu_id: menuId,
                quantity: quantity,
                notes: notes
            },
            success: function(response) {
                Swal.fire({
                    title: 'Mantap!',
                    text: 'Menu berhasil masuk keranjang',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = "{{ route('customer.menu') }}";
                });
            }
        });
    }
</script>
@endsection