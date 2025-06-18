<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CRUD\KaryaMusikController;
use App\Http\Controllers\CRUD\JadwalKegiatanController;
use App\Http\Controllers\CRUD\DokumentasiController;
use App\Http\Controllers\CRUD\KategoriController;

Route::apiResource('karya-musik', KaryaMusikController::class);
Route::apiResource('jadwal-kegiatan', JadwalKegiatanController::class);
Route::apiResource('dokumentasi', DokumentasiController::class);
Route::apiResource('kategori', KategoriController::class);

// Additional routes for specific actions
Route::get('karya-musik/data/genres', [KaryaMusikController::class, 'getGenres']);
Route::get('jadwal-kegiatan/data/date-range', [JadwalKegiatanController::class, 'getByDateRange']);
Route::patch('jadwal-kegiatan/{id}/peserta', [JadwalKegiatanController::class, 'updatePeserta']);
Route::post('dokumentasi/upload', [DokumentasiController::class, 'uploadFile']);
Route::get('dokumentasi/data/related', [DokumentasiController::class, 'getByRelated']);
Route::get('dokumentasi/data/gallery', [DokumentasiController::class, 'getGallery']);
Route::get('kategori/jenis/{jenis}', [KategoriController::class, 'getByJenis']);
Route::patch('kategori/{id}/toggle-aktif', [KategoriController::class, 'toggleAktif']);
Route::post('kategori/generate-slug', [KategoriController::class, 'generateSlug']);
