<?php

namespace App\Policies;

use App\Models\Pengajuan;
use App\Models\User;

class PengajuanPolicy
{
    /**
     * Logika untuk menentukan apakah user boleh melakukan aksi (Approve/Reject)
     * terhadap pengajuan tertentu.
     */
    public function process(User $user, Pengajuan $pengajuan)
    {
        // 1. Pastikan user adalah operator
        if ($user->role !== 'operator') {
            return false;
        }

        // 2. Cek Logika Otoritas vs Status Dokumen
        
        // JIKA TU: Hanya bisa aksi kalau statusnya 'menunggu_tu'
        if ($user->position === 'tu' && $pengajuan->status === 'menunggu_tu') {
            return true;
        }

        // JIKA KOORDINATOR: Hanya bisa aksi kalau statusnya 'menunggu_koordinator'
        if ($user->position === 'koordinator' && $pengajuan->status === 'menunggu_koordinator') {
            return true;
        }

        // JIKA VERIFIKATOR: Hanya bisa aksi kalau statusnya 'menunggu_verifikator'
        if ($user->position === 'verifikator' && $pengajuan->status === 'menunggu_verifikator') {
            return true;
        }

        // Selain kondisi di atas, user tidak boleh melakukan aksi (Cuma boleh lihat)
        return false;
    }
}