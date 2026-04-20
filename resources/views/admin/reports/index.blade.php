@extends('layouts.admin')

@section('title', 'Laporan Penjualan - Admin')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Dari</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 outline-none text-sm">
                </div>
                <div class="flex-1 w-full">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Sampai</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 outline-none text-sm">
                </div>
                <button type="submit" class="bg-black text-white px-6 py-2 rounded-lg font-bold text-sm h-[42px] w-full sm:w-auto">Filter</button>
            </form>
        </div>

        <div class="bg-green-500 p-6 rounded-xl text-white shadow-lg shadow-green-500/20">
            <p class="text-green-100 text-xs font-bold uppercase mb-1">Total Pendapatan</p>
            <h3 class="text-3xl font-black">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
        <table class="w-full text-left border-collapse text-sm">
            <thead class="bg-gray-50 border-b border-gray-200 uppercase text-gray-600">
                <tr>
                    <th class="p-4 font-bold">Waktu</th>
                    <th class="p-4 font-bold">Kode</th>
                    <th class="p-4 font-bold text-center">Meja</th>
                    <th class="p-4 font-bold text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr>
                    <td class="p-4 text-gray-600">{{ $order->updated_at->format('d M, H:i') }}</td>
                    <td class="p-4 font-mono font-bold text-xs">{{ $order->order_code }}</td>
                    <td class="p-4 text-center font-bold">{{ $order->table->number }}</td>
                    <td class="p-4 text-right font-bold text-green-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="p-8 text-center text-gray-500">Tidak ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection