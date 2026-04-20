<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    // 1. Menampilkan daftar menu
    public function index()
    {
        $menus = Menu::with('category')->latest()->get();
        return view('admin.menus.index', compact('menus'));
    }

    // 2. Menampilkan form tambah menu baru
    public function create()
    {
        $categories = Category::all();
        return view('admin.menus.create', compact('categories'));
    }

    // 3. Menyimpan data menu baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('image');
        $data['is_available'] = $request->has('is_available');

        // Simpan gambar jika ada
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        Menu::create($data);

        return redirect()->route('admin.menus.index')->with('success', 'Menu baru berhasil ditambahkan!');
    }

    // 4. Menampilkan form edit menu
    public function edit(Menu $menu)
    {
        $categories = Category::all();
        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    // 5. Menyimpan perubahan data menu
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('image');
        $data['is_available'] = $request->has('is_available');

        // Cek jika ada gambar baru yang diupload
        if ($request->hasFile('image')) {
            // Hapus gambar lama dari storage jika ada
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            // Simpan gambar baru
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $menu->update($data);

        return redirect()->route('admin.menus.index')->with('success', 'Data menu berhasil diperbarui!');
    }

    // 6. Menghapus menu
    public function destroy(Menu $menu)
    {
        // Hapus gambar dari storage sebelum datanya dihapus
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus!');
    }
}