<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboard;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboard;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('barang', \App\Http\Controllers\Admin\BarangController::class);
    Route::resource('user', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('rayon', \App\Http\Controllers\Admin\RayonController::class);
    Route::resource('rombel', \App\Http\Controllers\Admin\RombelController::class);
    Route::get('logs', [\App\Http\Controllers\Admin\LogController::class, 'index'])->name('logs');
    Route::get('laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan');
    Route::get('user/{user}/kartu', [\App\Http\Controllers\Admin\UserController::class, 'kartu'])->name('user.kartu');
    Route::get('barang/{barang}/label', [\App\Http\Controllers\Admin\BarangController::class, 'label'])->name('barang.label');

});

Route::middleware(['auth', 'role:petugas,admin'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [PetugasDashboard::class, 'index'])->name('dashboard');
    Route::resource('peminjaman', \App\Http\Controllers\Petugas\PeminjamanController::class);
    Route::post('peminjaman/{id}/kembalikan', [\App\Http\Controllers\Petugas\PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    Route::post('peminjaman/{id}/approve', [\App\Http\Controllers\Petugas\PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('peminjaman/{id}/tolak', [\App\Http\Controllers\Petugas\PeminjamanController::class, 'tolak'])->name('peminjaman.tolak');
    Route::get('denda', [\App\Http\Controllers\Petugas\DendaController::class, 'index'])->name('denda.index');
    Route::post('denda/{id}/lunas', [\App\Http\Controllers\Petugas\DendaController::class, 'lunas'])->name('denda.lunas');
    Route::get('pengembalian', [\App\Http\Controllers\Petugas\PeminjamanController::class, 'pengembalian'])->name('pengembalian.index');
});
    
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboard::class, 'index'])->name('dashboard');
    Route::get('katalog', [\App\Http\Controllers\Siswa\KatalogController::class, 'index'])->name('katalog');
    Route::post('request', [\App\Http\Controllers\Siswa\KatalogController::class, 'request'])->name('request');
    Route::get('history', [\App\Http\Controllers\Siswa\HistoryController::class, 'index'])->name('history');
    Route::get('denda', [\App\Http\Controllers\Siswa\HistoryController::class, 'denda'])->name('denda');
    Route::get('kartu', [\App\Http\Controllers\Siswa\HistoryController::class, 'kartu'])->name('kartu');
});

require __DIR__.'/auth.php';
