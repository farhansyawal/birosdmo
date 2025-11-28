<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CheckVerifiedUser
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Cek apakah user sedang login
        if (Auth::check()) {

            // 2. Cek apakah akun user NON-AKTIF (is_verified = 0 atau false)
            if (!Auth::user()->is_verified) {

                // 3. Jika non-aktif, paksa logout
                Auth::logout();

                // 4. Hapus sesi agar tidak bisa back
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // 5. Tendang ke halaman login dengan pesan error
                return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan oleh Admin.');
            }
        }

        return $next($request);
    }
}
