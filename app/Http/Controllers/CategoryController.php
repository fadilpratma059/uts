<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('items')->latest()->get();

        return view('categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        $category->load('items');

        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');

            if ($imagePath === false) {
                throw new RuntimeException('Foto kategori gagal disimpan.');
            }

            $validated['image_path'] = $imagePath;
        }

        unset($validated['image']);
        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');

            if ($imagePath === false) {
                throw new RuntimeException('Foto kategori gagal disimpan.');
            }

            $validated['image_path'] = $imagePath;
        }

        unset($validated['image']);
        $oldImagePath = $category->image_path;
        $category->update($validated);

        if (isset($imagePath) && $oldImagePath !== null && ! Storage::disk('public')->delete($oldImagePath)) {
            throw new RuntimeException('Foto kategori lama gagal dihapus.');
        }

        return redirect()->route('categories.show', $category)->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        // Karena di SQL kita set ON DELETE SET NULL, item di dalamnya aman & otomatis jadi "Belum Tersedia"
        $imagePath = $category->image_path;
        $category->delete();

        if ($imagePath !== null && ! Storage::disk('public')->delete($imagePath)) {
            throw new RuntimeException('Foto kategori gagal dihapus.');
        }

        return redirect()->route('categories.index')->with('success', 'Kategori dihapus. Data item terkait otomatis menjadi "Belum Tersedia".');
    }
}
