<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // ⬅️ WAJIB INI


class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.user.user', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name'       => 'required',
        'username'   => 'required|unique:users', // ⬅️ TAMBAH
        'email'      => 'required|email|unique:users',
        'password'   => 'required|min:6',
        'akun_role'  => 'required|in:admin,petugas,peminjam',
        'role'       => 'nullable|in:guru,siswa,petugas',
    ]);
    
    User::create([
        'name'       => $request->name,
        'username'   => $request->username, // ⬅️ TAMBAH
        'email'      => $request->email,
        'password'   => Hash::make($request->password),
        'akun_role'  => $request->akun_role,
        'role'       => $request->role,
    ]);

    return redirect()->route('admin.user.index')
        ->with('success', 'User berhasil ditambahkan');
}

    public function destroy($id)
{
    $user = User::findOrFail($id);

    // ❌ Admin tidak boleh dihapus
    if ($user->akun_role === 'admin') {
        return back()->with('error', 'Admin tidak boleh dihapus');
    }

    $user->delete();

    return back()->with('success', 'User berhasil dihapus');
}
public function edit($id)
{
    $user = User::findOrFail($id);
    return view('admin.user.edit', compact('user'));
}
public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name'       => 'required',
        'email'      => 'required|email|unique:users,email,' . $user->id,
        'akun_role'  => 'required|in:admin,petugas,peminjam',
        'role'       => 'nullable|in:guru,siswa,petugas',
        'password'   => 'nullable|min:6',
    ]);

    $data = [
        'name'      => $request->name,
        'email'     => $request->email,
        'akun_role' => $request->akun_role,
        'role'      => $request->role,
    ];

    // 🔐 password hanya diupdate kalau diisi
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    $user->update($data);

    return redirect()->route('admin.user.index')
        ->with('success', 'User berhasil diupdate');
}

}