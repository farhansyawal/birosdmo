<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\UserVerifiedNotification;
use Illuminate\Support\Facades\Log; // Jangan lupa import Log

class AdminPenggunaController extends Controller
{
    public function index()
    {
        // OPTIMASI: 
        // 1. select(): Hanya ambil kolom yang ditampilkan di view (Hemat Memori)
        // 2. paginate(): Jangan load semua data sekaligus (Hemat Query & Load Time)
        // 3. Jika nanti ada relasi (misal: role), tambahkan ->with('role') sebelum select
        
        $users = User::select('id', 'name', 'email', 'is_verified', 'created_at')
                     ->orderBy('created_at', 'desc')
                     ->paginate(15); // Menampilkan 15 data per halaman

        return view('admin.pengguna.index', compact('users'));
    }

    public function toggleStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean'
        ]);

        // Gunakan select untuk query update ringan, meski findOrFail sudah cukup cepat untuk single ID
        $user = User::select('id', 'is_verified', 'email', 'name')->findOrFail($id);

        $statusBaru = $request->status;
        $user->is_verified = $statusBaru;
        $user->save();

        if ($statusBaru == true) {
            try {
                $user->notify(new UserVerifiedNotification());
            } catch (\Throwable $e) {
                Log::error("Gagal mengirim notifikasi verifikasi ke user ID {$id}: " . $e->getMessage());
            }
        }

        $pesan = $statusBaru ? 'Akun berhasil diaktifkan.' : 'Akun berhasil dinonaktifkan.';

        return response()->json([
            'success' => true,
            'message' => $pesan
        ]);
    }
}