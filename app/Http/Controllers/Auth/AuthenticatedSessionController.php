<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest; // Pastikan ini ada jika pakai validasi request
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    // Ubah parameter jadi Request biasa agar lebih fleksibel menangani AJAX
    public function store(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Cek Manual: Apakah user ada?
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Akun dengan email tersebut tidak ditemukan.',
            ], 422);
        }

        // 3. Cek Manual: Status Verifikasi
        if (!$user->is_verified) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda belum terverifikasi. Mohon tunggu konfirmasi admin.',
            ], 422);
        }

        // 4. Tangkap nilai checkbox 'remember' (True/False)
        // Pastikan name di HTML adalah 'remember'
        // DEBUGGING: Cek apakah remember bernilai true/false
        $remember = $request->boolean('remember');

        // Hapus tanda komentar di bawah ini untuk tes
        // return response()->json(['status' => 'debug', 'remember_value' => $remember]); 

        // 4. Login dengan Remember Me
        if (Auth::attempt($request->only('email', 'password'), $remember)) {
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'title' => 'Login Berhasil!',
                'message' => 'Selamat datang kembali.',
                'redirect_url' => route('beranda'),
                // Kirim balik status remember untuk kita lihat di console browser
                'debug_remember' => $remember
            ]);
        }

        // Jika Password Salah
        return response()->json([
            'success' => false,
            'message' => 'Kata sandi yang Anda masukkan salah.',
        ], 422);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('beranda');
    }
}