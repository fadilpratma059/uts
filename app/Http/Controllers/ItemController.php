<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category')->latest()->get();
        $categories = Category::withCount('items')->latest()->get();

        return view('dashboard', compact('items', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');

            if ($imagePath === false) {
                throw new RuntimeException('Foto item gagal disimpan.');
            }

            $validated['image_path'] = $imagePath;
        }

        unset($validated['image']);
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        Item::create($validated);

        return redirect()->back()->with('success', 'Data item berhasil ditambahkan.');
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');

            if ($imagePath === false) {
                throw new RuntimeException('Foto item gagal disimpan.');
            }

            $validated['image_path'] = $imagePath;
        }

        unset($validated['image']);
        $oldImagePath = $item->image_path;
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $item->update($validated);

        if (isset($imagePath) && $oldImagePath !== null && ! Storage::disk('public')->delete($oldImagePath)) {
            throw new RuntimeException('Foto item lama gagal dihapus.');
        }

        return redirect()->back()->with('success', 'Data item berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        $imagePath = $item->image_path;
        $item->delete();

        if ($imagePath !== null && ! Storage::disk('public')->delete($imagePath)) {
            throw new RuntimeException('Foto item gagal dihapus.');
        }

        return redirect()->back()->with('success', 'Data item berhasil dihapus.');
    }
}
