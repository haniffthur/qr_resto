<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Di sinilah tempat untuk mendaftarkan rute API untuk aplikasi lu.
| Semua rute di file ini akan otomatis ditambahkan awalan "/api" 
| oleh Laravel.
|
*/

// Rute bawaan API Laravel (biarin aja buat jaga-jaga kalau nanti butuh)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ✅ WEBHOOK MIDTRANS
// Ingat: Karena ditulis di file api.php, URL yang terbentuk adalah: 
// namadomain.com/api/midtrans/callback
Route::post('/midtrans/callback', [CustomerController::class, 'paymentCallback']);