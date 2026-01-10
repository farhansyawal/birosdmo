<?php

use App\Http\Controllers\Admin\AdminBerandaController;
use App\Http\Controllers\Admin\AdminPenggunaController;
use App\Http\Controllers\Admin\AdminPengajuanController;
use App\Http\Controllers\Admin\AdminBeritaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\ReferensiController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\Api\DataMasterController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\AdminFormInputController;
use App\Http\Controllers\Admin\AdminLayananController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Operator\DashboardOperatorController;


// 
use App\Http\Controllers\BerandaController;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

// 
use App\Http\Controllers\Api\FormInputController;
use App\Http\Controllers\Operator\OperatorProfileController;
use App\Http\Controllers\Operator\PengajuanOperatorController;
use App\Http\Controllers\User\RiwayatPengajuanController;
use App\Http\Controllers\User\UserTrackingController;
use App\Http\Controllers\User\DashboardUserController;
use App\Http\Controllers\User\UserPengajuanController;
use App\Http\Controllers\User\UserProfileController;

// === HALAMAN UTAMA ===
Route::get('/', [BerandaController::class, 'index'])->name('beranda');

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
Route::post('/berita', [BeritaController::class, 'show'])->name('berita.show');
Route::get('/berita/{id}/detail', [App\Http\Controllers\BeritaController::class, 'getJson'])->name('berita.json');



Route::get('/compress', function () {

    // Path gambar asli
    $source = public_path('img/corporate.jpg');

    // Path gambar hasil kompres
    $destination = public_path('img/corporate-min.jpg');

    // Buat manager
    $manager = new ImageManager(new Driver());

    // Baca gambar
    $img = $manager->read($source);

    // Kompres TANPA resize (aman, tidak akan gepeng)
    // Hanya memperkecil kualitas → ukuran file mengecil, bentuk tetap
    $img->save($destination, quality: 60);

    return 'Selesai kompres tanpa merusak proporsi!';
});

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

    Route::get('/pengguna', [AdminPenggunaController::class, 'index'])->name('pengguna.index');
    Route::post('/pengguna/{id}/toggle-status', [AdminPenggunaController::class, 'toggleStatus'])
        ->name('pengguna.toggle');

    Route::post('layanan/{id}/toggle', [AdminLayananController::class, 'toggle'])->name('layanan.toggle');
    // Halaman Layanan
    Route::get('layanan', [AdminLayananController::class, 'index'])->name('layanan.index');
    Route::get('layanan/create', [AdminLayananController::class, 'create'])->name('layanan.create');
    Route::post('layanan/store', [AdminLayananController::class, 'store'])->name('layanan.store');
    Route::get('layanan/{id}/edit', [AdminLayananController::class, 'edit'])->name('layanan.edit');
    Route::put('layanan/{id}/update', [AdminLayananController::class, 'update'])->name('layanan.update');
    Route::delete('layanan/{id}/delete', [AdminLayananController::class, 'destroy'])->name('layanan.destroy');



    // Manajemen Pengajuan
    Route::get('/pengajuan', [AdminPengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/{id}', [AdminPengajuanController::class, 'show']); // API Detail
    Route::post('/pengajuan/{id}/update', [AdminPengajuanController::class, 'forceUpdateStatus']); // API Force Update
    Route::delete('/pengajuan/{id}', [AdminPengajuanController::class, 'destroy'])->name('pengajuan.destroy');

    // Toggle HARUS di atas resource CRUD supaya tidak tertimpa

    // Toggle HARUS diletakkan SETELAH resource

    // FORM INPUT DINAMIS
    Route::get('layanan/{layanan}/form-input', [AdminFormInputController::class, 'index'])->name('form-input.index');
    Route::post('layanan/{layanan}/form-input', [AdminFormInputController::class, 'store'])->name('form-input.store');
    Route::delete('form-input/{input}', [AdminFormInputController::class, 'destroy'])->name('form-input.destroy');



    // Berita
    Route::get('berita', [AdminBeritaController::class, 'index'])->name('berita.index');
    Route::post('berita/store', [AdminBeritaController::class, 'store'])->name('berita.store');
    Route::get('berita/{id}/json', [AdminBeritaController::class, 'getJson'])->name('berita.json');
    Route::put('berita/{id}/update', [AdminBeritaController::class, 'update'])
        ->name('berita.update');
    Route::delete('berita/{id}/delete', [AdminBeritaController::class, 'destroy'])->name('berita.destroy');


    // Profile
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile/update', [AdminProfileController::class, 'update'])->name('profile.update');
});


// === DASHBOARD OPERATOR ===
Route::middleware(['auth', 'role:operator'])->prefix('operator')->name('operator.')->group(function () {

    // --- NON-API ROUTES (Halaman View) ---
    Route::get('/dashboard', [\App\Http\Controllers\Operator\DashboardOperatorController::class, 'index'])->name('dashboard');
    Route::get('/pengajuan', [\App\Http\Controllers\Operator\PengajuanOperatorController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/export', [PengajuanOperatorController::class, 'exportExcel'])->name('pengajuan.export'); // Non-API

    // ===============================================
    // API Endpoints (Dihapus semua prefix berlebihan)
    // URL Final yang diakses JS: /operator/api/pengajuan/{id}
    // ===============================================
    Route::prefix('api/pengajuan')->group(function () {

        // GET detail data (digunakan oleh openDetail/openVerifikasi JS - Fetch 1)
        Route::get('{id}', [\App\Http\Controllers\Operator\PengajuanOperatorController::class, 'show']);

        // GET verifikasi checklist state (digunakan oleh openVerifikasi - Fetch 2)
        Route::get('{id}/verifikasi', [\App\Http\Controllers\Operator\PengajuanOperatorController::class, 'getVerifikasi']);

        // POST: Menyimpan Checklist Verifikasi
        Route::post('{id}/verifikasi', [\App\Http\Controllers\Operator\PengajuanOperatorController::class, 'saveVerifikasi']);

        // POST: Update Status & Catatan
        Route::post('{id}/status', [\App\Http\Controllers\Operator\PengajuanOperatorController::class, 'updateStatus'])
            ->name('api.pengajuan.updateStatus');

    });


    // Profile
    Route::get('/profile', [OperatorProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile/update', [OperatorProfileController::class, 'update'])->name('profile.update');
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


    // submit form pengajuan
    Route::post('/pages/pengajuan/submit/{layanan}', [UserPengajuanController::class, 'submit'])
        ->name('pages.layanan.submit');

    Route::get('/pages/riwayat-pengajuan', [RiwayatPengajuanController::class, 'index'])
        ->name('pages.riwayat-pengajuan');

    // 1. Route untuk GET Detail (Show)
    Route::get('/pengajuan/{id}', [RiwayatPengajuanController::class, 'show']);

    // 2. Route untuk UPDATE Perbaikan (Revisi) -> Tambahkan Ini
    Route::put('/pengajuan/{id}/update', [RiwayatPengajuanController::class, 'update'])->name('pengajuan.update');



    // Tracking
    Route::get('/pages/tracking', [UserTrackingController::class, 'index'])->name('tracking');

    // Route sementara untuk cek data (Hapus nanti setelah berhasil)



    // Profile
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');
});

// Hapus route ini nanti setelah masalah selesai
Route::get('/cek-json', function() {
    // Kita ambil 1 data terakhir
    $data = \App\Models\PengajuanLayanan::latest()->first();
    
    return [
        'ID' => $data->id,
        // Ini akan menampilkan data MENTAH dari database tanpa diproses Laravel
        'RAW_DATA_UMUM' => $data->getRawOriginal('data_umum'), 
        'TEST_KEYWORD' => '18429/Setjen/4123'
    ];
});

// === API untuk mengambil form dinamis ===
Route::get('/api/layanan/{id}/fields', [FormInputController::class, 'getFields'])
    ->name('api.layanan.fields');



// routes/web.php
Route::get('/api/unit-kerja', [ReferensiController::class, 'getUnitKerja']);

Route::get('/api/jabatan', [ReferensiController::class, 'getJabatan']);
Route::get('/api/pangkat', [ReferensiController::class, 'getPangkat']);
Route::get('/api/pensiun', [ReferensiController::class, 'getPensiun']);
Route::get('/api/kenaikanPangkat', [ReferensiController::class, 'getKenaikanPangkat']);



// === PROFIL USER ===
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
