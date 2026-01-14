<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminManageController extends Controller
{
    public function index()
    {
        $admins = Admin::orderBy('id_admin', 'desc')->get();
        return view('admin.manage-admin.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.manage-admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admin,username',
            // SECURITY FIX: Password minimal 8 karakter dengan kompleksitas
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'role' => 'required|in:Super Admin,Layanan,Humas,Pengaduan',
        ], [
            'password.regex' => 'Password harus mengandung minimal 1 huruf besar, 1 huruf kecil, dan 1 angka.',
        ]);

        Admin::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.manage-admin.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.manage-admin.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('admin', 'username')->ignore($admin->id_admin, 'id_admin'),
            ],
            // SECURITY FIX: Password minimal 8 karakter dengan kompleksitas
            'password' => 'nullable|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'role' => 'required|in:Super Admin,Layanan,Humas,Pengaduan',
        ], [
            'password.regex' => 'Password harus mengandung minimal 1 huruf besar, 1 huruf kecil, dan 1 angka.',
        ]);

        $data = [
            'nama' => $request->nama,
            'username' => $request->username,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.manage-admin.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);

        if ($admin->id_admin === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $admin->delete();
        return back()->with('success', 'Admin berhasil dihapus.');
    }
}
