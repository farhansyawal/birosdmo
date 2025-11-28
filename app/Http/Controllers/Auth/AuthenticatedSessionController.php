<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * 🔹 Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * 🔹 Tangani proses login.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Cek apakah user ada dan sudah diverifikasi
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Akun tidak ditemukan.',
            ]);
        }

        // Cek status verifikasi
        if (!$user->is_verified) {
            return back()->withErrors([
                'email' => 'Akun Anda belum terverifikasi, tunggu konfirmasi dari admin.',
            ])->onlyInput('email');
        }

        // Jika sudah diverifikasi → lanjut login
        $request->authenticate();
        $request->session()->regenerate();

        session()->flash('success', 'Berhasil login! Selamat datang kembali.');

        return redirect()->route('beranda');
    }

    /**
     * 🔹 Logout user dari aplikasi.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('beranda');
    }
}
