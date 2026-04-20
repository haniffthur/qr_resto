@extends('layouts.kasir')

@section('title', 'Dashboard Kasir - POS')

@push('head')
    <meta http-equiv="refresh" content="10">
@endpush

@section('content')
    @if($orders->isEmpty())
        <div class="flex flex-col items-center justify-center h-96 text-gray-500 bg-gray-800/30 rounded-3xl border-2 border-dashed border-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <p class="text-xl font-medium">Menunggu pesanan dari pelanggan...</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($orders as $order)
                <div class="bg-gray-800 rounded-2xl p-5 border-t-4 {{ $order->payment_status == 'paid' ? 'border-green-500' : ($order->status == 'pending' ? 'border-red-500' : 'border-yellow-400') }} shadow-xl flex flex-col justify-between">
                    
                    <div>
                        <div class="flex justify-between items-start mb-4 border-b border-gray-700 pb-3">
                            <div>
                                <h2 class="text-2xl font-black text-white">Meja {{ $order->table->number }}</h2>
                                <p class="text-[10px] text-gray-500 font-mono mt-1">{{ $order->order_code }}</p>
                            </div>
                            <div class="text-right flex flex-col items-end space-y-1">
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $order->status == 'pending' ? 'bg-red-500/20 text-red-400' : 'bg-yellow-400/20 text-yellow-300' }}">
                                    Cook: {{ $order->status }}
                                </span>
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $order->payment_status == 'unpaid' ? 'bg-red-500/20 text-red-400' : 'bg-green-500/20 text-green-400' }}">
                                    Pay: {{ $order->payment_status }}
                                </span>
                            </div>
                        </div>

                        <ul class="space-y-3 mb-6">
                            @foreach($order->items as $item)
                            <li class="flex justify-between items-start text-sm">
                                <div class="flex items-start">
                                    <span class="bg-gray-700 text-white font-bold px-2 py-0.5 rounded mr-3 text-xs">{{ $item->quantity }}x</span>
                                    <span class="font-medium text-gray-200">{{ $item->menu->name }}</span>
                                </div>
                                <span class="text-gray-500 text-xs">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-700">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-400 text-xs font-bold uppercase">Total Tagihan</span>
                            <span class="text-xl font-bold text-orange-500">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>

                        <div class="space-y-2">
                            <form action="{{ route('kasir.update-status', $order->id) }}" method="POST">
                                @csrf
                                @if($order->status == 'pending')
                                    <input type="hidden" name="status" value="processing">
                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold py-2.5 rounded-xl transition">
                                        👨‍🍳 Mulai Masak
                                    </button>
                                @endif
                            </form>

                            @if($order->payment_status == 'unpaid')
                                <form action="{{ route('kasir.pay', $order->id) }}" method="POST" onsubmit="return confirm('Proses pembayaran?');">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-500 text-white text-xs font-bold py-3 rounded-xl transition shadow-lg shadow-green-500/20">
                                        💵 Terima Pembayaran
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection