@extends('layouts.kasir')

@section('title', 'Riwayat Pesanan Hari Ini')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-white">Riwayat Transaksi Hari Ini</h2>
        <a href="{{ route('kasir.dashboard') }}" class="text-sm bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg transition">
            &larr; Kembali ke Antrean
        </a>
    </div>

    <div class="bg-gray-800 rounded-2xl overflow-hidden shadow-xl border border-gray-700">
        <table class="w-full text-left border-collapse text-sm">
            <thead class="bg-gray-700 text-gray-300 uppercase text-[10px] font-bold">
                <tr>
                    <th class="p-4">Waktu</th>
                    <th class="p-4">Kode</th>
                    <th class="p-4 text-center">Meja</th>
                    <th class="p-4 text-right">Total</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-750 transition">
                    <td class="p-4 text-gray-400">{{ $order->updated_at->format('H:i') }}</td>
                    <td class="p-4 font-mono text-orange-400">{{ $order->order_code }}</td>
                    <td class="p-4 text-center font-bold">#{{ $order->table->number }}</td>
                    <td class="p-4 text-right font-bold text-green-400">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="p-4 text-center">
                        <a href="{{ route('kasir.receipt', $order->id) }}" class="inline-block bg-gray-600 hover:bg-gray-500 px-3 py-1 rounded text-[10px] font-bold uppercase tracking-tighter transition">
                            Cetak Ulang
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-500 italic">Belum ada transaksi selesai hari ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection