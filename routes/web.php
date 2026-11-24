<?php

use App\Http\Controllers\BukuController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/buku');

/*
|--------------------------------------------------------------------------
| Rute Manajemen Buku Perpustakaan
|--------------------------------------------------------------------------
| URI statis seperti /buku/create HARUS didefinisikan sebelum URI dinamis
| /buku/{kode}. Jika terbalik, kata "create" akan tertangkap sebagai nilai
| parameter {kode} dan halaman form tambah tidak akan pernah tampil.
*/
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
Route::post('/buku/reset', [BukuController::class, 'reset'])->name('buku.reset');
Route::get('/buku/{kode}', [BukuController::class, 'show'])->name('buku.show');
Route::get('/buku/{kode}/edit', [BukuController::class, 'edit'])->name('buku.edit');
Route::match(['put', 'patch'], '/buku/{kode}', [BukuController::class, 'update'])->name('buku.update');
Route::delete('/buku/{kode}', [BukuController::class, 'destroy'])->name('buku.destroy');
