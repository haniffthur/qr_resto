<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        // Ambil pesanan yang belum selesai, urutkan dari yang paling lama antre
        $orders = Order::with(['table', 'items.menu'])
            ->whereIn('status', ['pending', 'processing'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('kitchen.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        // Update status pesanan
        $request->validate(['status' => 'required|in:pending,processing,completed,cancelled']);
        
        $order->update([
            'status' => $request->status
        ]);

        return redirect()->back();
    }
    public function pay(Order $order)
    {
        // Ubah status jadi lunas & selesai
        $order->update([
            'payment_status' => 'paid',
            'status' => 'completed'
        ]);

        // Arahkan ke halaman cetak struk
        return redirect()->route('kasir.receipt', $order->id);
    }

    // Fungsi baru untuk menampilkan struk
    public function receipt(Order $order)
    {
        // Pastikan order beserta relasinya (meja & item) terbawa
        $order->load(['table', 'items.menu']);
        return view('kitchen.receipt', compact('order'));
    }
    public function history()
{
    // Ambil pesanan yang sudah lunas (paid) HANYA untuk hari ini
    $orders = Order::with(['table', 'items.menu'])
        ->where('payment_status', 'paid')
        ->whereDate('updated_at', today())
        ->latest()
        ->get();

    return view('kitchen.history', compact('orders'));
}
}