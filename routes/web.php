<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/scan/{number}/{token}', [OrderController::class, 'scan'])->name('scan');

// Route halaman menu
Route::get('/menu', [OrderController::class, 'index'])->name('menu.index');