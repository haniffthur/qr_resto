<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::orderBy('number', 'asc')->get();
        return view('admin.tables.index', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|unique:tables,number'
        ]);

        Table::create([
            'number' => $request->number,
            'token' => Str::random(10), // Generate token unik otomatis untuk keamanan QR
            'status' => 'available'
        ]);

        return redirect()->back()->with('success', 'Meja berhasil ditambahkan!');
    }
    
    // Nanti kita bisa tambahkan fungsi hapus meja juga
}