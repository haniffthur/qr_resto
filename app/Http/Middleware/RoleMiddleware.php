<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Cek apakah user sudah login dan role-nya sesuai dengan yang diminta
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        // Kalau role nggak sesuai, tendang ke halaman 403 (Forbidden)
        abort(403, 'Akses Ditolak. Lu bukan ' . $role . '!');
    }
}