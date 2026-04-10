<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
