<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use App\Models\Table;
use App\Models\OrderItem;
use App\Events\OrderPaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class KitchenController extends Controller
{
    /**
     * Tampilan Antrean Kasir
     */
    public function index()
    {
        $orders = Order::with(['table', 'orderItems.menu'])
                        ->where('status', '!=', 'completed')
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('kasir.dashboard', compact('orders'));
    }

    /**
     * Selesaikan Pesanan (Arsip ke Riwayat)
     */
    public function complete(Order $order)
    {
        if ($order->payment_status !== 'paid') {
            return back()->with('error', 'Pesanan belum lunas!');
        }

        $order->update(['status' => 'completed']);
        return back()->with('success', 'Pesanan selesai.');
    }

    /**
     * Halaman POS Take Away
     */
    public function pos()
    {
        $menus = Menu::all();
        return view('kasir.pos', compact('menus'));
    }

    /**
     * Proses Transaksi POS (Cash & Midtrans)
     */
    public function storePos(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'cart' => 'required|string',
            'payment_method' => 'required|in:cash,midtrans',
            'cash_received' => 'nullable|numeric'
        ]);

        $cart = json_decode($request->cart, true);
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        DB::beginTransaction();
        try {
            // Setup Meja Virtual Take Away
            $table = Table::firstOrCreate(
                ['number' => 'TAKE-AWAY'],
                ['token' => 'ta-virtual', 'status' => 'available']
            );

            $orderCode = 'TA-' . time();
            $order = Order::create([
                'table_id'       => $table->id,
                'order_code'     => $orderCode,
                'customer_name'  => $request->customer_name . ' (Take Away)',
                'total_price'    => $total,
                'status'         => 'pending', 
                'payment_status' => ($request->payment_method === 'cash' ? 'paid' : 'pending'),
                'note'           => "Metode: " . strtoupper($request->payment_method),
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id'  => $item['id'],
                    'quantity' => $item['quantity'],
                    'price'    => $item['price'],
                ]);
            }

            // JIKA PEMBAYARAN MIDTRANS
            if ($request->payment_method === 'midtrans') {
                Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
                Config::$isSanitized = true;
                Config::$is3ds = true;

                $params = [
                    'transaction_details' => ['order_id' => $orderCode, 'gross_amount' => (int)$total],
                    'customer_details' => ['first_name' => $request->customer_name, 'email' => 'customer@pos.com'],
                ];

                $snapToken = Snap::getSnapToken($params);
                $order->update(['snap_token' => $snapToken]);

                DB::commit();
                return response()->json(['status' => 'midtrans', 'snap_token' => $snapToken]);
            }

            // JIKA PEMBAYARAN CASH
            DB::commit();
            broadcast(new OrderPaid($order));

            return response()->json(['status' => 'cash', 'message' => 'Transaksi Tunai Berhasil']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function history()
    {
        $orders = Order::with(['table'])->where('status', 'completed')->orderBy('updated_at', 'desc')->get();
        return view('kasir.history', compact('orders'));
    }

    public function receipt(Order $order)
    {
        $order->load(['table', 'orderItems.menu']);
        return view('kasir.receipt', compact('order'));
    }
}