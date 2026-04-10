<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('items')->get();
        return view('admin.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255|unique:categories,name',
            'division' => 'required|string',
        ], [
            'name.required'     => 'Name wajib diisi.',
            'name.unique'       => 'Nama category sudah ada.',
            'division.required' => 'Division PJ wajib dipilih.',
        ]);

        Category::create($request->only('name', 'division'));

        return redirect()->route('admin.categories')->with('success', 'Category berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'     => 'required|string|max:255|unique:categories,name,' . $category->id,
            'division' => 'required|string',
        ], [
            'name.required'     => 'Name wajib diisi.',
            'name.unique'       => 'Nama category sudah ada.',
            'division.required' => 'Division PJ wajib dipilih.',
        ]);

        $category->update($request->only('name', 'division'));

        return redirect()->route('admin.categories')->with('success', 'Category berhasil diupdate.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Category berhasil dihapus.');
    }
}
