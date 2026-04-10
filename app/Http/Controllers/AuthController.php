<?php

namespace App\Http\Controllers;

use App\Exports\ItemsExport;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('welcome', ['showLoginModal' => true]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))){
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'operator') {
                return redirect()->route('operator.dashboard');
            } else {
                return redirect()->route('error.image');
            }
        }

        return redirect()->route('login')
            ->withErrors([
                'email' => 'Email atau password salah.',
            ])
            ->withInput();
    }

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    public function adminCategories()
    {
        $categories = Category::all();

        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'division' => ['required', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'division.required' => 'Division PJ wajib dipilih.',
        ]);

        Category::create($data);

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    public function updateCategory(Request $request, $id)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'division' => ['required', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'division.required' => 'Division PJ wajib dipilih.',
        ]);

        $category = Category::findOrFail($id);
        $category->update($data);

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function adminItems()
    {
        $items = Item::with('category')->get();
        $categories = Category::all();
        return view('admin.items', compact('items', 'categories'));
    }

    public function storeItem(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:255'],
            'total'       => ['required', 'integer', 'min:0'],
            'repair'      => ['required', 'integer', 'min:0'],
        ]);

        Item::create($data);

        return redirect()->route('admin.items')->with('success', 'Item berhasil ditambahkan.');
    }

    public function editItem($id)
    {
        return response()->json(Item::findOrFail($id));
    }

    public function updateItem(Request $request, $id)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:255'],
            'total'       => ['required', 'integer', 'min:0'],
            'new_repair'  => ['required', 'integer', 'min:0'],
        ]);

        $item = Item::findOrFail($id);
        $item->update([
            'category_id' => $data['category_id'],
            'name'        => $data['name'],
            'total'       => $data['total'],
            'repair'      => $item->repair + $data['new_repair'],
        ]);

        return redirect()->route('admin.items')->with('success', 'Item berhasil diperbarui.');
    }

    public function exportItems()
    {
        return Excel::download(new ItemsExport, 'items_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function dashboard()
    {
        $totalItems = 0; // Placeholder, ganti dengan model items jika ada
        $activeLendings = 0; // Placeholder, ganti dengan model lendings jika ada
        $pendingReturns = 0; // Placeholder, ganti dengan model returns jika ada
        return view('operator.dashboard', compact('totalItems', 'activeLendings', 'pendingReturns'));
    }

    public function errorImage()
    {
        return view('error');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}

