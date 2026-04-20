<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Table;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // -------------------------------------------------------
    // HALAMAN BERANDA
    // -------------------------------------------------------
    public function home()
    {
        // Ambil semua kategori (untuk filter)
        $categories = Category::withCount('menus')->get();

        // Menu populer: ambil 6 saja untuk home
        $popularMenus = Menu::where('is_popular', true)->take(6)->get();

        // Semua menu dikelompokkan per kategori (untuk filter interaktif)
        $allMenus = Menu::with('category')->get();

        return view('customer.home', compact('categories', 'popularMenus', 'allMenus'));
    }

    // -------------------------------------------------------
    // HALAMAN DAFTAR MENU (GRID)
    // -------------------------------------------------------
    public function index(Request $request)
    {
        $categories = Category::with('menus')->get();
        return view('customer.menu', compact('categories'));
    }

    // -------------------------------------------------------
    // HALAMAN DETAIL MENU
    // -------------------------------------------------------
    public function show($id)
    {
        $menu = Menu::with('category')->findOrFail($id);
        return view('customer.detail', compact('menu'));
    }

    // -------------------------------------------------------
    // SCAN QR
    // -------------------------------------------------------
    public function scan($number, $token)
    {
        $table = Table::where('number', $number)->where('token', $token)->first();

        if (!$table) {
            return redirect('/')->with('error', 'Meja tidak valid.');
        }

        session(['table_id' => $table->id, 'table_number' => $table->number]);

        return redirect()->route('customer.home');
    }

    // -------------------------------------------------------
    // ✅ TAMBAH KE KERANJANG
    // -------------------------------------------------------
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

        // Hitung total
        $totalQty   = 0;
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalQty   += $item['quantity'];
            $totalPrice += $item['price'] * $item['quantity'];
        }

        return response()->json([
            'status'      => 'success',
            'message'     => 'Menu berhasil ditambah!',
            'cart_count'  => $totalQty,
            'total_price' => number_format($totalPrice, 0, ',', '.'),
        ]);
    }

    // -------------------------------------------------------
    // ✅ HAPUS DARI KERANJANG
    // -------------------------------------------------------
    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        $totalQty   = 0;
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalQty   += $item['quantity'];
            $totalPrice += $item['price'] * $item['quantity'];
        }

        return response()->json([
            'status'      => 'success',
            'cart_count'  => $totalQty,
            'total_price' => number_format($totalPrice, 0, ',', '.'),
            'cart'        => $cart,
        ]);
    }

    // -------------------------------------------------------
    // TAMPILAN HALAMAN KERANJANG
    // -------------------------------------------------------
    public function viewCart()
    {
        $cart  = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('customer.cart', compact('cart', 'total'));
    }

    // -------------------------------------------------------
    // ✅ PLACE ORDER (Buat pesanan baru)
    // -------------------------------------------------------
    public function placeOrder(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json(['status' => 'error', 'message' => 'Keranjang kosong!'], 400);
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $order = Order::create([
            'table_id'       => session('table_id'),
            'total_price'    => $total,
            'status'         => 'pending',
            'payment_status' => 'unpaid',
        ]);

        foreach ($cart as $menuId => $item) {
            OrderItem::create([
                'order_id'  => $order->id,
                'menu_id'   => $menuId,
                'quantity'  => $item['quantity'],
                'price'     => $item['price'],
                'sub_total' => $item['price'] * $item['quantity'],
            ]);
        }

        session()->forget('cart');

        return response()->json([
            'status'  => 'success',
            'message' => 'Pesanan berhasil dibuat!',
            'order_id'=> $order->id,
        ]);
    }
}