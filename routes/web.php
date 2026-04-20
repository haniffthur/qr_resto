<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\KitchenController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\TableController;
use App\Models\Menu;
use App\Models\Category;
use App\Http\Controllers\CustomerController;

// --- ROUTE UNTUK PELANGGAN (GUEST) ---
Route::get('/scan/{number}/{token}', [CustomerController::class, 'scan'])->name('scan');

// ✅ FIX: Cart routes HARUS di luar middleware check.table
// supaya bisa diakses meskipun belum scan QR (untuk testing/dev)
Route::post('/cart/add', [CustomerController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove/{id}', [CustomerController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/cart', [CustomerController::class, 'viewCart'])->name('customer.cart');

// GRUP PELANGGAN (butuh session meja)
Route::middleware(['check.table'])->group(function () {
    Route::get('/home', [CustomerController::class, 'home'])->name('customer.home');
    Route::get('/menu', [CustomerController::class, 'index'])->name('customer.menu');
    Route::get('/menu/{id}', [CustomerController::class, 'show'])->name('customer.menu.detail');
    Route::post('/order', [CustomerController::class, 'placeOrder'])->name('customer.order');
});

// --- PENGATUR LALU LINTAS LOGIN ---
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'admin') return redirect()->route('admin.dashboard');
    elseif ($role === 'kasir') return redirect()->route('kasir.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- RUANGAN KHUSUS ADMIN ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $stats = [
            'total_orders'    => \App\Models\Order::whereDate('created_at', today())->count(),
            'total_revenue'   => \App\Models\Order::whereDate('created_at', today())->where('payment_status', 'paid')->sum('total_price'),
            'pending_orders'  => \App\Models\Order::where('status', 'pending')->count(),
            'available_tables'=> \App\Models\Table::where('status', 'available')->count(),
        ];
        return view('admin.dashboard', compact('stats'));
    })->name('dashboard');

    Route::resource('menus', MenuController::class);
    Route::resource('tables', TableController::class);
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
});

// --- RUANGAN KHUSUS KASIR ---
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [KitchenController::class, 'index'])->name('dashboard');
    Route::post('/order/{order}/status', [KitchenController::class, 'updateStatus'])->name('update-status');
    Route::post('/order/{order}/pay', [KitchenController::class, 'pay'])->name('pay');
    Route::get('/order/{order}/receipt', [KitchenController::class, 'receipt'])->name('receipt');
    Route::get('/history', [KitchenController::class, 'history'])->name('history');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';