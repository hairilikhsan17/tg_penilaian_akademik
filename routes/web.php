<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MatakuliahController;
use App\Http\Controllers\KomponenPenilaianController;
use App\Http\Controllers\NilaiMahasiswaController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () { return redirect()->route('login'); });

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform')->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.perform')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboards
    Route::get('/dosen', [DashboardController::class, 'dosen'])->name('dosen.dashboard');
    Route::get('/mahasiswa', [DashboardController::class, 'mahasiswa'])->name('mahasiswa.dashboard');
    Route::post('/mahasiswa/go', [StudentController::class, 'goByNim'])->name('mahasiswa.go');
    
    // Mahasiswa - Profile & Nilai
    Route::get('/mahasiswa/profile', [StudentController::class, 'profile'])->name('mahasiswa.profile');
    Route::put('/mahasiswa/profile', [StudentController::class, 'updateProfile'])->name('mahasiswa.profile.update');
    Route::delete('/mahasiswa/profile/foto', [StudentController::class, 'deleteFotoProfil'])->name('mahasiswa.profile.deleteFoto');
    Route::get('/mahasiswa/nilai', [StudentController::class, 'nilai'])->name('mahasiswa.nilai');
    Route::get('/mahasiswa/khs', [StudentController::class, 'khs'])->name('mahasiswa.khs');
    Route::get('/mahasiswa/cetak-khs', [StudentController::class, 'cetakKhs'])->name('mahasiswa.cetak.khs');

    // Dosen - Laporan Nilai
    Route::get('/dosen/laporan-nilai', [ReportController::class, 'index'])->name('dosen.laporan');
    Route::get('/dosen/laporan-nilai/pdf', [ReportController::class, 'exportPdf'])->name('dosen.laporan.pdf');
    Route::get('/dosen/laporan-nilai/excel', [ReportController::class, 'exportExcel'])->name('dosen.laporan.excel');
    
    // Dosen - Profil
    Route::get('/dosen/profil', [DashboardController::class, 'dosenProfil'])->name('dosen.profil');
    Route::put('/dosen/profil', [DashboardController::class, 'updateDosenProfil'])->name('dosen.profil.update');
    Route::delete('/dosen/profil/foto', [DashboardController::class, 'deleteFotoProfil'])->name('dosen.profil.deleteFoto');

    // Dosen - Kelola Mahasiswa & Mata Kuliah
    Route::resource('mahasiswas', MahasiswaController::class);
    Route::get('mahasiswas/{mahasiswa}/password', [MahasiswaController::class, 'showPassword'])->name('mahasiswas.password');
    Route::post('mahasiswas/{mahasiswa}/password', [MahasiswaController::class, 'updatePassword'])->name('mahasiswas.password.update');
    Route::resource('matakuliahs', MatakuliahController::class);

    // Dosen - Komponen Penilaian per Mata Kuliah
    Route::get('/dosen/komponen-penilaian', [KomponenPenilaianController::class, 'index'])->name('komponen.index');
    Route::get('matakuliahs/{matakuliah}/komponen', [KomponenPenilaianController::class, 'create'])->name('komponen.create');
    Route::post('matakuliahs/{matakuliah}/komponen', [KomponenPenilaianController::class, 'storeOrUpdate'])->name('komponen.store');

    // Dosen - Input Nilai Mahasiswa per Mata Kuliah
    Route::get('/dosen/nilai-mahasiswa', [NilaiMahasiswaController::class, 'list'])->name('nilai.list');
    Route::get('matakuliahs/{matakuliah}/nilai', [NilaiMahasiswaController::class, 'index'])->name('nilai.index');
    Route::post('matakuliahs/{matakuliah}/nilai', [NilaiMahasiswaController::class, 'storeOrUpdate'])->name('nilai.store');

    // Mahasiswa - Lihat Nilai & Cetak (untuk dosen/admin)
    Route::get('mahasiswa/{mahasiswa}/nilai', [StudentController::class, 'showNilai'])->name('mahasiswa.show.nilai');
    Route::get('mahasiswa/{mahasiswa}/print', [StudentController::class, 'printKhs'])->name('mahasiswa.print');
});
