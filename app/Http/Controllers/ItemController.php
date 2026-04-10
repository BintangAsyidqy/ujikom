<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category')->get();
        $categories = Category::all();
        return view('admin.items', compact('items', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'total'       => 'required|integer|min:0',
            'repair'      => 'required|integer|min:0',
        ]);

        Item::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'total'       => $request->total,
            'repair'      => $request->repair,
        ]);

        return redirect()->route('admin.items')->with('success', 'Item berhasil ditambahkan.');
    }

    public function edit(Item $item)
    {
        return response()->json($item);
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'total'       => 'required|integer|min:0',
            'new_repair'  => 'nullable|integer|min:0',
        ], [
            'category_id.required' => 'Category wajib dipilih.',
            'category_id.exists'   => 'Category tidak valid.',
            'name.required'        => 'Name wajib diisi.',
            'total.required'       => 'Total wajib diisi.',
            'total.integer'        => 'Total harus berupa angka.',
            'total.min'            => 'Total tidak boleh negatif.',
            'new_repair.integer'   => 'New broke item harus berupa angka.',
            'new_repair.min'       => 'New broke item tidak boleh negatif.',
        ]);

        $item->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'total'       => $request->total,
            'repair'      => $item->repair + (int) $request->new_repair,
        ]);

        return redirect()->route('admin.items')->with('success', 'Item berhasil diupdate.');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('admin.items')->with('success', 'Item berhasil dihapus.');
    }
}
