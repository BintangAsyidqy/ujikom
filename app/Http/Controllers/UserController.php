<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function export(string $role)
    {
        $users = User::where('role', $role)->get();
        $filename = $role . '-accounts_' . now()->format('Ymd_His') . '.xlsx';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['Name', 'Email', 'Password'], null, 'A1');

        foreach ($users as $i => $user) {
            $sheet->fromArray([
                $user->name,
                $user->email,
                $user->password_plain ?? 'This account already edited the password',
            ], null, 'A' . ($i + 2));
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
    }

    public function resetPassword(User $user)
    {
        $emailPrefix = substr($user->email, 0, 4);
        $numbers     = preg_replace('/[^0-9]/', '', $user->email);
        $password    = $emailPrefix . $numbers;

        $user->update([
            'password'       => Hash::make($password),
            'password_plain' => $password,
        ]);

        return redirect()->back()->with('success', 'Password berhasil direset.')->with('generated_password', $password);
    }

    public function adminIndex()
    {
        $users = User::where('role', 'admin')->get();
        return view('admin.users-admin', compact('users'));
    }

    public function operatorIndex()
    {
        $users = User::where('role', 'operator')->get();
        return view('admin.users-operator', compact('users'));
    }

    public function operatorSelf()
    {
        $user = auth()->user();
        return view('operator.users', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role'  => 'required|in:admin,operator',
        ], [
            'name.required'  => 'Name wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah digunakan.',
            'role.required'  => 'Role wajib dipilih.',
        ]);

        $emailPrefix = substr($request->email, 0, 4);
        $numbers     = preg_replace('/[^0-9]/', '', $request->email);
        $password    = $emailPrefix . $numbers;

        User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'role'           => $request->role,
            'password'       => Hash::make($password),
            'password_plain' => $password,
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan.')->with('generated_password', $password);
    }

    public function edit(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'new_password' => 'nullable|string|min:4',
        ], [
            'name.required'  => 'Name wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah digunakan.',
        ]);

        $data = $request->only('name', 'email');
        if ($request->filled('new_password')) {
            $data['password']       = Hash::make($request->new_password);
            $data['password_plain'] = null;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
}
