<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'icon' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048'
    ]);

    $data = $request->all();
    
    // ✅ OTOMATIS: Buat slug dari nama kategori
    // Misal: "Makanan Utama" jadi "makanan-utama"
    $data['slug'] = Str::slug($request->name);

    if ($request->hasFile('icon')) {
        $data['icon'] = $request->file('icon')->store('categories', 'public');
    }

    Category::create($data);

    return back()->with('success', 'Kategori baru berhasil ditambahkan!');
}

    public function update(Request $request, Category $category)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'icon' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048'
    ]);

    $data = $request->all();
    
    // ✅ Update slug kalau namanya berubah
    $data['slug'] = Str::slug($request->name);

    if ($request->hasFile('icon')) {
        if ($category->icon) {
            \Storage::disk('public')->delete($category->icon);
        }
        $data['icon'] = $request->file('icon')->store('categories', 'public');
    }

    $category->update($data);

    return back()->with('success', 'Kategori berhasil diupdate!');
}

    public function destroy(Category $category)
    {
        if ($category->icon) {
            Storage::disk('public')->delete($category->icon);
        }
        $category->delete();
        return back()->with('success', 'Kategori dihapus.');
    }
}