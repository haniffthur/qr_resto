@extends('layouts.kasir')

@section('title', 'Riwayat Pesanan')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-black text-white uppercase tracking-tight">Riwayat Transaksi</h2>
            <p class="text-gray-400 text-xs mt-1 italic">Pesanan yang sudah selesai</p>
        </div>
        <a href="{{ route('kasir.dashboard') }}" class="text-xs bg-gray-800 hover:bg-gray-700 border border-gray-700 px-4 py-2 rounded-lg font-bold uppercase tracking-widest transition">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-[#1e2638] rounded-2xl overflow-hidden shadow-2xl border border-gray-700">
        <table class="w-full text-left border-collapse text-sm">
            <thead class="bg-gray-900 text-gray-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="p-4">Waktu</th>
                    <th class="p-4">Kode Order</th>
                    <th class="p-4 text-center">Meja</th>
                    <th class="p-4 text-right">Total</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-800 transition">
                    <td class="p-4 text-gray-400 font-medium">{{ $order->updated_at->format('H:i') }}</td>
                    <td class="p-4 font-mono text-orange-400 font-bold tracking-wider">{{ $order->order_code }}</td>
                    <td class="p-4 text-center font-black text-gray-200">#{{ $order->table->number }}</td>
                    <td class="p-4 text-right font-black text-emerald-400">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="p-4 text-center">
                        <a href="{{ route('kasir.receipt', $order->id) }}" target="_blank" class="inline-block bg-gray-700 hover:bg-gray-600 text-white px-3 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition border border-gray-600 shadow-md">
                            <i class="fa-solid fa-print mr-1"></i> Cetak
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-500 italic font-bold">Belum ada transaksi selesai hari ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection