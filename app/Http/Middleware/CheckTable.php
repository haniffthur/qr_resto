<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTable
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('table_id')) {
            return redirect('/')->with('error', 'Silakan scan QR Code di meja terlebih dahulu.');
        }

        return $next($request); 
    }
}