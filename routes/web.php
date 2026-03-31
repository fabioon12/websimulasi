<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\guru\MateriController as GuruMateriController;
use App\Http\Controllers\guru\SubmateriController as GuruSubmateriController;
use App\Http\Controllers\siswa\MateriController as SiswaMateriController;
use App\Http\Controllers\guru\ProyekController as GuruProyekController;
use App\Http\Controllers\siswa\KatalogproyekController as SiswaKatalogproyekController;
use App\Http\Controllers\siswa\ProyekController as SiswaProyekController;
use App\Http\Controllers\guru\ReviewTugasController;
use App\Http\Controllers\siswa\LeaderboardController;
use App\Http\Controllers\guru\LeaderboardController as GuruLeaderboardController;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('landingpage.landing');
    })->name('landing');
    Route::get('/about', function () {
        return view('landingpage.about');
    })->name('about');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard'); 
    });
    // Guru
    Route::middleware(['role:guru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
        Route::get('materi', [GuruMateriController::class, 'index'])->name('materi.dashboard');
        Route::get('materi/create', [GuruMateriController::class, 'create'])->name('materi.create');
        Route::post('materi/store', [GuruMateriController::class, 'store'])->name('materi.store');
        Route::get('materi/{id}/edit', [GuruMateriController::class, 'edit'])->name('materi.edit');
        Route::put('materi/{id}', [GuruMateriController::class, 'update'])->name('materi.update');
        Route::delete('materi/{id}', [GuruMateriController::class, 'destroy'])->name('materi.destroy');
        Route::get('materi/{materi_id}/submateri', [GuruSubmateriController::class, 'index'])->name('submateri.dashboard');
        Route::get('materi/{materi_id}/submateri/create', [GuruSubmateriController::class, 'create'])->name('submateri.create');
        Route::post('materi/submateri/store', [GuruSubmateriController::class, 'store'])->name('submateri.store');
        Route::get('materi/{materi_id}/submateri/{id}/edit', [GuruSubmateriController::class, 'edit'])->name('submateri.edit');
        Route::put('materi/submateri/{id}', [GuruSubmateriController::class, 'update'])->name('submateri.update');
        Route::delete('materi/submateri/{id}', [GuruSubmateriController::class, 'destroy'])->name('submateri.destroy');
        Route::post('/upload-materi-image', [GuruSubmateriController::class, 'uploadImage'])->name('submateri.uploadImage');

        Route::get('proyek/dashboard', [GuruProyekController::class, 'index'])->name('proyek.dashboard');
        Route::get('proyek/create', [GuruProyekController::class, 'create'])->name('proyek.create');
        Route::post('proyek/store', [GuruProyekController::class, 'store'])->name('proyek.store');
        Route::delete('proyek/{id}', [GuruProyekController::class, 'destroy'])->name('proyek.destroy');
        Route::get('proyek/{id}/roadmap', [GuruProyekController::class, 'roadmap'])->name('proyek.roadmap');
        Route::post('proyek/roadmap/store', [GuruProyekController::class, 'storeRoadmap'])->name('proyek.roadmap.store');
        Route::delete('proyek/roadmap/{id}', [GuruProyekController::class, 'destroyRoadmap'])->name('proyek.roadmap.destroy');
        Route::post('/trix/upload', [GuruProyekController::class, 'uploadTrix'])->name('proyek.trix.upload');
        Route::get('/proyek/{id}/edit', [GuruProyekController::class, 'edit'])->name('proyek.edit');
        Route::put('/proyek/{id}', [GuruProyekController::class, 'update'])->name('proyek.update');
        Route::put('proyek/roadmap/{id}', [GuruProyekController::class, 'updateRoadmap'])->name('proyek.roadmap.update');
        Route::get('proyek/review-tugas', [ReviewTugasController::class, 'index'])->name('review.index');
        Route::patch('proyek/review-tugas/{id}', [ReviewTugasController::class, 'update'])->name('review.update');

        Route::get('leaderboard', [GuruLeaderboardController::class, 'index'])->name('leaderboard.index');
    });
    // Siswa
    Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
        Route::get('materi', [SiswaMateriController::class, 'index'])->name('materi.dashboard');
        Route::post('/materi/{id}/enroll', [SiswaMateriController::class, 'enroll'])->name('materi.enroll');
        Route::get('materi/{id}/belajar/{sub_id?}', [SiswaMateriController::class, 'learn'])->name('materi.learn');
        Route::post('materi/{materi_id}/complete/{sub_id}', [SiswaMateriController::class, 'completeSubMateri'])->name('materi.complete');
        Route::get('katalog', [SiswaKatalogproyekController::class, 'index'])->name('katalog.index');
        Route::get('katalog/{id}', [SiswaKatalogproyekController::class, 'show'])->name('katalog.show');
        Route::post('proyek/join', [SiswaKatalogproyekController::class, 'join'])->name('proyek.join');
        Route::get('/proyek/pengerjaan/{id}', [SiswaKatalogproyekController::class, 'show'])->name('show.pengerjaan');
        Route::get('proyek', [SiswaProyekController::class, 'index'])->name('proyek.index');
        Route::get('/proyek/workspace/{id}', [SiswaProyekController::class, 'show'])->name('proyek.pengerjaan');
        Route::post('/upload-trix', [SiswaProyekController::class, 'uploadTrix'])->name('trix.upload');
        Route::post('/tugas/submit', [SiswaProyekController::class, 'store'])->name('tugas.store');

        Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
        
    });
});


