<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanLayanan;

class UserTrackingController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim($request->input('q'));
        $data = null;
        $timeline = [];

        if ($keyword) {
            $data = PengajuanLayanan::with(['user', 'layanan'])
                ->where(function($query) use ($keyword) {
                    
                    // 1. Cari berdasarkan ID Persis
                    $query->where('id', $keyword);

                    // 2. LOGIKA PENCARIAN CERDAS (SEGMENT SEARCH)
                    // Jika keyword mengandung '/', kita pecah jadi bagian-bagian
                    if (str_contains($keyword, '/')) {
                        $parts = explode('/', $keyword); // Pecah: ["18429", "Setjen", "4123"]
                        
                        // Cari baris dimana data_umum mengandung SEMUA bagian tersebut
                        $query->orWhere(function($subQuery) use ($parts) {
                            foreach ($parts as $part) {
                                // Abaikan bagian kosong (misal ada spasi ganda)
                                if (trim($part) !== '') {
                                    $subQuery->where('data_umum', 'LIKE', '%' . trim($part) . '%');
                                }
                            }
                        });
                    } else {
                        // Jika tidak ada garis miring, cari biasa
                        $query->orWhere('data_umum', 'LIKE', "%{$keyword}%");
                    }
                })
                ->first();

            if ($data) {
                $timeline = $this->generateTimeline($data);
            }
        }

        return view('user.pages.tracking.index', compact('data', 'keyword', 'timeline'));
    }

    // ... (Function generateTimeline TETAP SAMA, tidak perlu diubah) ...
    private function generateTimeline($data) {
        $steps = [
            'pending' => ['label' => 'Dokumen Dikirim', 'desc' => 'Pengajuan diterima sistem TU.', 'icon' => 'fa-paper-plane'],
            'menunggu_koordinator' => ['label' => 'Validasi TU', 'desc' => 'Pengecekan kelengkapan administrasi.', 'icon' => 'fa-clipboard-check'],
            'menunggu_verifikator' => ['label' => 'Review Koordinator', 'desc' => 'Disposisi dan arahan teknis.', 'icon' => 'fa-user-tie'],
            'menunggu_kabiro' => ['label' => 'Verifikasi Teknis', 'desc' => 'Pemeriksaan validitas berkas.', 'icon' => 'fa-search'],
            'final' => ['label' => 'Keputusan Akhir', 'desc' => 'Penerbitan dokumen / Surat keluar.', 'icon' => 'fa-flag-checkered'],
        ];

        $currentLevel = 0;
        $status = $data->status;
        $isRejected = ($status == 'ditolak' || $status == 'perlu_revisi');

        switch ($status) {
            case 'pending': $currentLevel = 1; break;
            case 'menunggu_koordinator': $currentLevel = 2; break;
            case 'menunggu_verifikator': $currentLevel = 3; break;
            case 'menunggu_kabiro': $currentLevel = 4; break;
            case 'diterima': $currentLevel = 5; break;
            case 'ditolak': case 'perlu_revisi': $currentLevel = 99; break;
        }

        $result = [];
        $i = 1;
        foreach ($steps as $key => $info) {
            $stepStatus = 'pending';
            if ($isRejected) {
                $stepStatus = ($i == 1) ? 'completed' : 'pending';
            } elseif ($currentLevel == 5) {
                $stepStatus = 'completed';
            } else {
                if ($i < $currentLevel) $stepStatus = 'completed';
                elseif ($i == $currentLevel) $stepStatus = 'current';
            }

            if ($key == 'final' && $status == 'diterima') {
                $info['label'] = 'Selesai & Diterbitkan';
                $info['desc'] = 'Dokumen Anda telah selesai diproses.';
            }

            $result[] = [
                'status' => $stepStatus,
                'label' => $info['label'],
                'desc' => $info['desc'],
                'icon' => $info['icon'],
                'date' => ($stepStatus == 'current' || $stepStatus == 'completed') ? $data->updated_at->format('d M Y') : '-'
            ];
            $i++;
        }

        return [
            'steps' => $result,
            'is_rejected' => $isRejected,
            'reject_reason' => $data->catatan,
            'reject_type' => $status
        ];
    }
}