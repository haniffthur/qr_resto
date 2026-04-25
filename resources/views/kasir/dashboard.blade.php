@extends('layouts.kasir')

@section('title', 'Antrean Kasir')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-black text-slate-800 tracking-tight">Antrean Pesanan</h2>
        <p class="text-slate-500 text-sm mt-1">Pantau transaksi masuk secara <span class="font-bold text-orange-500">Real-time</span></p>
    </div>
    
    <div class="flex gap-3">
        <div class="bg-white px-5 py-3 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-500 flex items-center justify-center text-lg">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Total Antrean</span>
                <span id="order-count" class="text-2xl font-black text-slate-800 leading-none">{{ $orders->count() }}</span>
            </div>
        </div>
    </div>
</div>

<div id="order-container">
    @if($orders->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($orders as $order)
        <div class="bg-white rounded-2xl border-t-4 border-t-orange-500 border-x border-b border-gray-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden group relative">
            
            <div class="p-5 border-b border-gray-100 bg-slate-50/50">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-[10px] font-black text-orange-500 uppercase tracking-widest bg-orange-100 px-2 py-1 rounded-md mb-2 inline-block">Meja</span>
                        <h3 class="text-3xl font-black text-slate-800 leading-none">{{ $order->table->number ?? '—' }}</h3>
                        <p class="text-xs font-mono text-slate-400 mt-2">{{ $order->order_code }}</p>
                    </div>
                    
                    <div class="text-right">
                        @if($order->payment_status === 'paid')
                            <div class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 border border-emerald-200 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                <i class="fa-solid fa-circle-check"></i> Lunas
                            </div>
                        @else
                            <div class="inline-flex items-center gap-1.5 bg-orange-50 text-orange-600 border border-orange-200 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider animate-pulse">
                                <i class="fa-solid fa-clock"></i> Pending
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="p-5 space-y-3 mb-auto">
                @foreach($order->orderItems as $item)
                <div class="flex items-start justify-between text-sm group-hover:bg-slate-50 p-2 -mx-2 rounded-lg transition-colors">
                    <div class="flex gap-3">
                        <span class="bg-slate-100 text-slate-600 w-6 h-6 rounded-md flex items-center justify-center text-xs font-black shrink-0 border border-gray-200">
                            {{ $item->quantity }}
                        </span>
                        <div>
                            <p class="font-bold text-slate-700 leading-tight">{{ $item->menu->name }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">@ Rp{{ number_format($item->price) }}</p>
                        </div>
                    </div>
                    <span class="font-bold text-slate-800">Rp{{ number_format($item->price * $item->quantity) }}</span>
                </div>
                @endforeach
            </div>

            <div class="p-5 bg-white border-t border-dashed border-gray-200">
                <div class="flex justify-between items-end mb-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Tagihan</span>
                    <span class="text-2xl font-black text-orange-600 leading-none">Rp{{ number_format($order->total_price) }}</span>
                </div>

                @if($order->payment_status === 'paid')
                    <form action="{{ route('kasir.complete', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3.5 rounded-xl font-bold text-sm uppercase tracking-wide transition shadow-lg shadow-orange-500/30 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-check-double"></i> Selesaikan Pesanan
                        </button>
                    </form>
                @else
                    <button disabled class="w-full bg-slate-100 text-slate-400 py-3.5 rounded-xl font-bold text-sm uppercase tracking-wide cursor-not-allowed border border-slate-200 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-spinner fa-spin-pulse"></i> Menunggu Bayar
                    </button>
                @endif
            </div>
            
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-3xl border border-dashed border-gray-300 py-32 flex flex-col items-center justify-center text-center shadow-sm">
        <div class="w-24 h-24 bg-orange-50 text-orange-300 rounded-full flex items-center justify-center text-5xl mb-6">
            <i class="fa-solid fa-mug-hot"></i>
        </div>
        <h3 class="text-xl font-black text-slate-700 mb-2">Antrean Sedang Kosong</h3>
        <p class="text-slate-400 text-sm max-w-sm">Pesanan pelanggan akan otomatis muncul di sini saat mereka melakukan checkout.</p>
    </div>
    @endif
</div>

{{-- SCRIPT WEBSOCKET / LARAVEL ECHO TETAP ADA --}}
<script type="module">
    Echo.channel('kasir-channel')
        .listen('OrderPaid', (e) => {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, "text/html");
                    
                    let newContainer = doc.getElementById('order-container');
                    if (newContainer && document.getElementById('order-container')) {
                        document.getElementById('order-container').innerHTML = newContainer.innerHTML;
                    }

                    let newCount = doc.getElementById('order-count');
                    if (newCount && document.getElementById('order-count')) {
                        document.getElementById('order-count').innerHTML = newCount.innerHTML;
                    }
                });
        });
</script>
@endsection