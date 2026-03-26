<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /* =======================
     * FORM
     * ======================= */
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    /* =======================
     * LOGIN
     * ======================= */
    public function loginProcess(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
            'remember' => 'nullable'
        ]);

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        if (!Auth::attempt([
            $loginField => $request->login,
            'password'  => $request->password,
        ], $request->remember)) {
            return back()->withErrors([
                'login' => 'Username / Email atau password salah'
            ])->withInput();
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->akun_role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Login berhasil');
        }

        if ($user->akun_role === 'petugas') {
            return redirect()->route('petugas.dashboard')->with('success', 'Login berhasil');
        }
        if ($user->akun_role === 'peminjam') {
            return redirect()->route('peminjam.dashboard')->with('success', 'Login berhasil');
        }

        return redirect('/')->with('success', 'Login berhasil');
    }

    /* =======================
     * REGISTER
     * ======================= */
    public function registerProcess(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role'     => 'required|in:guru,siswa',
            'kelas'    => 'required_if:role,siswa|nullable|string|max:255',
            'bidang_ajar' => 'required_if:role,guru|nullable|string|max:255',
            'nomor_telepon' => 'required|string|max:30',
        ]);

        $baseUsername = Str::slug($request->name);
        $username = $baseUsername;
        $suffix = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $suffix;
            $suffix++;
        }

        User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'username'       => $username,
            'password'       => Hash::make($request->password),
            'role'           => $request->role,
            'akun_role'      => 'peminjam',
            'kelas'          => $request->role === 'siswa' ? $request->kelas : null,
            'bidang_ajar'    => $request->role === 'guru' ? $request->bidang_ajar : null,
            'nomor_telepon'  => $request->nomor_telepon,
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login');
    }

    /* =======================
     * LOGOUT
     * ======================= */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout berhasil');
    }
}
