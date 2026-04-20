<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Set default tanggal hari ini kalau belum ada filter
        $startDate = $request->start_date ?? Carbon::today()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::today()->format('Y-m-d');

        // Ambil data order yang sudah LUNAS (paid) berdasarkan rentang tanggal
        $orders = Order::with('table')
            ->where('payment_status', 'paid')
            ->whereBetween('updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->latest()
            ->get();

        // Hitung total pendapatan di rentang waktu tersebut
        $totalRevenue = $orders->sum('total_price');

        return view('admin.reports.index', compact('orders', 'totalRevenue', 'startDate', 'endDate'));
    }
}