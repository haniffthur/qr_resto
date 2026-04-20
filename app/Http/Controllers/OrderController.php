<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * PROSES CHECKOUT: Mengubah isi keranjang (Session) jadi data di Database.
     */
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong!');
        }

        // Mulai Database Transaction agar kalau ada error, data tidak setengah tersimpan
        DB::beginTransaction();

        try {
            // 1. Simpan Data Induk Pesanan
            $order = Order::create([
                'table_id'     => session('table_id'),
                'total_price'  => $this->calculateTotal($cart),
                'status'       => 'pending', // pending, processing, completed, cancelled
                'customer_name'=> $request->customer_name ?? 'Pelanggan Meja ' . session('table_number'),
                'note'         => $request->note,
            ]);

            // 2. Simpan Detail Pesanan (Item-itemnya)
            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id'  => $id,
                    'quantity' => $details['quantity'],
                    'price'    => $details['price'],
                ]);
            }

            DB::commit();

            // 3. Kosongkan Keranjang setelah berhasil pesan
            session()->forget('cart');

            return redirect()->route('customer.home')->with('success', 'Pesanan berhasil dikirim! Mohon tunggu sebentar.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * DASHBOARD KASIR: Menampilkan semua pesanan masuk.
     */
    public function adminIndex()
    {
        // Ambil pesanan terbaru dengan data meja dan detail menu
        $orders = Order::with(['table', 'orderItems.menu'])
                        ->latest()
                        ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * UPDATE STATUS: Kasir mengubah status pesanan (misal dari pending ke processing).
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pesanan diperbarui ke: ' . $request->status);
    }

    /**
     * HELPER: Menghitung total harga dari session cart.
     */
    private function calculateTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}