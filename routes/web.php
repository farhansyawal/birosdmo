<?php

use App\Http\Controllers\Admin\AdminBerandaController;
use App\Http\Controllers\Admin\AdminPenggunaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\ReferensiController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\Api\DataMasterController;
use App\Http\Controllers\User\DashboardUserController;
use App\Http\Controllers\User\UserPengajuanController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\AdminFormInputController;
use App\Http\Controllers\Admin\AdminLayananController;
use App\Http\Controllers\Operator\DashboardOperatorController;


// 
use App\Http\Controllers\Api\FormInputController;
use App\Http\Controllers\Operator\PengajuanOperatorController;
use App\Http\Controllers\User\RiwayatPengajuanController;

// === HALAMAN UTAMA ===
Route::get('/', function () {
    return view('beranda');
})->name('beranda');

// === PUBLIK ===
Route::get('/layanan', [LayananController::class, 'index'])->name('layanan');

Route::get('/peraturan', function () {
    return view('pages.peraturan');
})->name('peraturan');

// Kontak
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak.index');
Route::post('/kontak', [KontakController::class, 'store'])->name('kontak.store');

// Berita
Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
Route::post('/berita', [BeritaController::class, 'store'])->name('berita.store');


// === DASHBOARD BERDASARKAN ROLE ===
Route::get('/dashboard', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('beranda');
    }

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'operator' => redirect()->route('operator.dashboard'),
        'user' => redirect()->route('user.dashboard'),
        default => redirect()->route('beranda'),
    };
})->middleware(['auth'])->name('dashboard');

// === DASHBOARD ADMIN ===
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // beranda
    Route::get('/beranda', [AdminBerandaController::class, 'edit'])->name('beranda.edit');
    Route::post('/beranda', [AdminBerandaController::class, 'update'])->name('beranda.update');


    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

    Route::get('/pages/pengguna', [AdminPenggunaController::class, 'index'])->name('pages.pengguna.index');
    Route::post('/pages/pengguna/{id}/toggle-status', [AdminPenggunaController::class, 'toggleStatus'])
        ->name('pages.pengguna.toggle');

    Route::post('layanan/{id}/toggle', [AdminLayananController::class, 'toggle'])->name('layanan.toggle');
    // Halaman Layanan
    Route::get('layanan', [AdminLayananController::class, 'index'])->name('layanan.index');
    Route::get('layanan/create', [AdminLayananController::class, 'create'])->name('layanan.create');
    Route::post('layanan/store', [AdminLayananController::class, 'store'])->name('layanan.store');
    Route::get('layanan/{id}/edit', [AdminLayananController::class, 'edit'])->name('layanan.edit');
    Route::put('layanan/{id}/update', [AdminLayananController::class, 'update'])->name('layanan.update');
    Route::delete('layanan/{id}/delete', [AdminLayananController::class, 'destroy'])->name('layanan.destroy');

    // Toggle HARUS di atas resource CRUD supaya tidak tertimpa

    // Toggle HARUS diletakkan SETELAH resource

    // FORM INPUT DINAMIS
    Route::get('layanan/{layanan}/form-input', [AdminFormInputController::class, 'index'])->name('form-input.index');
    Route::post('layanan/{layanan}/form-input', [AdminFormInputController::class, 'store'])->name('form-input.store');
    Route::delete('form-input/{input}', [AdminFormInputController::class, 'destroy'])->name('form-input.destroy');
});


// === DASHBOARD OPERATOR ===
Route::middleware(['auth', 'role:operator'])->prefix('operator')->name('operator.')->group(function () {
    Route::get('/dashboard', [DashboardOperatorController::class, 'index'])->name('dashboard');
    Route::get('/pengajuan', [PengajuanOperatorController::class, 'index'])->name('pengajuan.index');

    Route::get('/operator/api/pengajuan/{id}', [PengajuanOperatorController::class, 'show']);
    // 🔹 Tambahkan ini
    Route::get('/api/pengajuan/{id}', function ($id) {
        $pengajuan = App\Models\PengajuanLayanan::with(['user', 'layanan'])->find($id);

        if (!$pengajuan) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'user' => $pengajuan->user->nama_pengguna ?? '-',
            'layanan' => $pengajuan->layanan->nama ?? '-',
            'tanggal' => $pengajuan->created_at->format('d M Y'),
            'status' => ucfirst($pengajuan->status),
            'data_umum' => json_decode($pengajuan->data_umum, true),
            'data_layanan' => json_decode($pengajuan->data_layanan, true),
        ]);
    })->name('pengajuan.api.show');

    Route::post('/api/pengajuan/{id}/status', [App\Http\Controllers\Operator\PengajuanOperatorController::class, 'updateStatus'])
        ->name('api.pengajuan.updateStatus');

    Route::get('/api/pengajuan/{id}/verifikasi', [App\Http\Controllers\Operator\PengajuanOperatorController::class, 'getVerifikasi']);
    Route::post('/api/pengajuan/{id}/verifikasi', [App\Http\Controllers\Operator\PengajuanOperatorController::class, 'saveVerifikasi']);

    Route::get('/pengajuan/export', [PengajuanOperatorController::class, 'exportExcel'])
        ->name('pengajuan.export');
});

// === DASHBOARD USER ===
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [DashboardUserController::class, 'index'])->name('dashboard');

    // Pengajuan Layanan
    // Halaman pengajuan
    Route::get('/pages/pengajuan', [UserPengajuanController::class, 'index'])
        ->name('pages.pengajuan');

    Route::get('/pages/riwayat-pengajuan', [RiwayatPengajuanController::class, 'index'])
        ->name('pages.riwayat-pengajuan');
    Route::get('/riwayat/{id}', [\App\Http\Controllers\User\RiwayatPengajuanController::class, 'show']);

    // submit form pengajuan
    Route::post('/pages/pengajuan/submit/{layanan}', [UserPengajuanController::class, 'submit'])
        ->name('pages.layanan.submit');
});

// === API untuk mengambil form dinamis ===
Route::get('/api/layanan/{id}/fields', [FormInputController::class, 'getFields'])
    ->name('api.layanan.fields');



// routes/web.php
Route::get('/api/unit-kerja', [ReferensiController::class, 'getUnitKerja']);

Route::get('/api/jabatan', [ReferensiController::class, 'getJabatan']);
Route::get('/api/pangkat', [ReferensiController::class, 'getPangkat']);

// === PROFIL USER ===
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
