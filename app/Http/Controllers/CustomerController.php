<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Table;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\DB;
use App\Events\OrderPaid;

class CustomerController extends Controller
{
    /**
     * HALAMAN BERANDA (HOME)
     */
    public function home()
    {
        // 1. Ambil TOP 3 Terpopuler berdasarkan total_sold
        // Kita pakai try-catch kecil buat jaga-jaga kalau kolom 'status' belum di-migrate
        try {
            $topMenus = Menu::where('status', 'available')
                            ->where('total_sold', '>', 0)
                            ->orderBy('total_sold', 'desc')
                            ->take(3)
                            ->get();
        } catch (\Exception $e) {
            // Fallback kalau kolom status belum ada
            $topMenus = Menu::orderBy('total_sold', 'desc')->take(3)->get();
        }

        // 2. Ambil Kategori dan Menunya
        $categories = Category::with(['menus' => function($q) {
            // Pastikan cuma menu yang tersedia yang muncul
            if (\Illuminate\Support\Facades\Schema::hasColumn('menus', 'status')) {
                $q->where('status', 'available');
            }
        }])->get();

        return view('customer.home', compact('topMenus', 'categories'));
    }

    /**
     * HALAMAN DAFTAR MENU (GRID)
     */
    public function index(Request $request)
    {
        $categories = Category::with(['menus' => function($q) {
            if (\Illuminate\Support\Facades\Schema::hasColumn('menus', 'status')) {
                $q->where('status', 'available');
            }
        }])->get();

        return view('customer.menu', compact('categories'));
    }

    /**
     * DETAIL MENU
     */
    public function show($id)
    {
        $menu = Menu::with('category')->findOrFail($id);
        return view('customer.detail', compact('menu'));
    }

    /**
     * SCAN QR CODE MEJA
     */
    public function scan($number, $token)
    {
        $table = Table::where('number', $number)->where('token', $token)->first();

        if (!$table) {
            return redirect('/')->with('error', 'Meja tidak valid.');
        }

        // Jika pindah meja, reset keranjang lama
        if (session('table_id') && session('table_id') != $table->id) {
            session()->forget('cart');
        }

        session([
            'table_id' => $table->id, 
            'table_number' => $table->number
        ]);

        return redirect()->route('customer.home');
    }

    /**
     * AJAX: TAMBAH KE KERANJANG
     */
    public function addToCart(Request $request)
    {
        $request->validate(['menu_id' => 'required|exists:menus,id']);

        $menu = Menu::findOrFail($request->menu_id);
        $cart = session()->get('cart', []);
        $id = $request->menu_id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name'     => $menu->name,
                'quantity' => 1,
                'price'    => $menu->price,
                'image'    => $menu->image,
            ];
        }

        session()->put('cart', $cart);

        $totalQty = collect($cart)->sum('quantity');
        $totalPrice = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return response()->json([
            'status'      => 'success',
            'message'     => 'Menu berhasil ditambah!',
            'cart_count'  => $totalQty,
            'total_price' => number_format($totalPrice, 0, ',', '.'),
        ]);
    }

    /**
     * AJAX: HAPUS DARI KERANJANG
     */
    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        $totalQty = collect($cart)->sum('quantity');
        $totalPrice = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return response()->json([
            'status'      => 'success',
            'cart_count'  => $totalQty,
            'total_price' => number_format($totalPrice, 0, ',', '.'),
            'cart'        => $cart,
        ]);
    }

    /**
     * TAMPILAN KERANJANG
     */
    public function viewCart()
    {
        $cart  = session()->get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return view('customer.cart', compact('cart', 'total'));
    }

    /**
     * PROSES CHECKOUT MIDTRANS
     */
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return response()->json(['status' => 'error', 'message' => 'Keranjang kosong!'], 400);
        }

        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        DB::beginTransaction();

        try {
            $orderCode = 'WN-' . session('table_number', '00') . '-' . time();
            
            $order = Order::create([
                'table_id'       => session('table_id'),
                'order_code'     => $orderCode,
                'customer_name'  => 'Pelanggan Meja ' . session('table_number', '00'),
                'total_price'    => $total,
                'status'         => 'pending',
                'payment_status' => 'pending',
                'note'           => $request->note ?? null,
            ]);

            foreach ($cart as $id => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id'  => $id,
                    'quantity' => $item['quantity'],
                    'price'    => $item['price'],
                ]);
            }

            // MIDTRANS CONFIG
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id'     => $orderCode,
                    'gross_amount' => (int)$total,
                ],
                'customer_details' => [
                    'first_name' => 'Meja ' . session('table_number', '00'),
                    'email'      => 'cust' . time() . '@resto.com',
                ],
            ];

            $snapToken = Snap::getSnapToken($params);
            $order->update(['snap_token' => $snapToken]);

            DB::commit();
            session()->forget('cart');

            return response()->json([
                'status'     => 'success',
                'snap_token' => $snapToken,
                'order_code' => $orderCode
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * WEBHOOK MIDTRANS
     */
    public function paymentCallback(Request $request)
    {
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;

        if (!$orderId) return response()->json(['message' => 'No order ID'], 200);

        $serverKey = env('MIDTRANS_SERVER_KEY');
        $signature = hash('sha512', $orderId . $payload['status_code'] . $payload['gross_amount'] . $serverKey);

        if ($signature !== $payload['signature_key']) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('order_code', $orderId)->first();
        if (!$order) return response()->json(['message' => 'Order not found'], 404);

        $transactionStatus = $payload['transaction_status'];

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $order->update(['payment_status' => 'paid']);
            
            // Trigger WebSocket untuk Kasir
            broadcast(new OrderPaid($order));
            
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $order->update(['payment_status' => 'failed']);
        }

        return response()->json(['message' => 'Status Updated']);
    }
}