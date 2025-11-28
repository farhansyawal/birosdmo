<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\UserVerifiedNotification;

class AdminPenggunaController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        return view('admin.pages.pengguna.index', compact('users'));
    }

    public function toggleStatus(Request $request, $id)
    {
        // 1. Validasi input (pastikan mengirim boolean true/false)
        $request->validate([
            'status' => 'required|boolean'
        ]);

        $user = User::findOrFail($id);

        // 2. Simpan status baru sesuai request dari tombol
        $statusBaru = $request->status;
        $user->is_verified = $statusBaru;
        $user->save();

        // 3. Logika Notifikasi (Opsional)
        // Hanya kirim notifikasi jika status diubah menjadi AKTIF (true)
        if ($statusBaru == true) {
            try {
                $user->notify(new UserVerifiedNotification());
            } catch (\Throwable $e) {
                // Log error jika email gagal terkirim, agar sistem tidak crash
                Log::error("Gagal mengirim notifikasi verifikasi ke user ID {$id}: " . $e->getMessage());
            }
        }

        // 4. Siapkan pesan respon
        $pesan = $statusBaru ? 'Akun berhasil diaktifkan.' : 'Akun berhasil dinonaktifkan.';

        return response()->json([
            'success' => true,
            'message' => $pesan
        ]);
    }
}
