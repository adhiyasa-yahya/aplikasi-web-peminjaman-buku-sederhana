<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Master\BookController;
use App\Http\Controllers\Api\V1\Master\BookTypeController;
use App\Http\Controllers\Api\V1\Master\AnggotaController;
use App\Http\Controllers\Api\V1\Pinjaman\PinjamanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function() {

    Route::prefix('master')->group(function () {
        Route::get('/book/type/data', [BookTypeController::class, 'data'])->name('book.type.data'); 
        Route::post('/book/type/save', [BookTypeController::class, 'save'])->name('book.type.save'); 
        
        Route::get('/book/data', [BookController::class, 'data'])->name('book.data'); 
        Route::get('/book/get-tipe-buku', [BookController::class, 'getTypeBook'])->name('book.type'); 
        Route::post('/book/save', [BookController::class, 'save'])->name('book.save'); 
        Route::delete('/book/{id}', [BookController::class, 'destory'])->name('book.delete'); 
    });

    Route::prefix('peminjaman')->group(function () {
        Route::get('data', [PinjamanController::class, 'data'])->name('pinjaman.data'); 
        Route::get('get-anggota', [PinjamanController::class, 'getAnggota'])->name('pinjaman.get-anggota'); 
        Route::get('get-book', [PinjamanController::class, 'getBook'])->name('pinjaman.get-book'); 
        Route::post('upprovel', [PinjamanController::class, 'upproveOrReject'])->name('pinjaman.approvel'); 
        Route::post('pengembalian', [PinjamanController::class, 'pengembalian'])->name('pinjaman.pengembalian'); 
        Route::post('save', [PinjamanController::class, 'save'])->name('pinjaman.save'); 
    });

    Route::get('/anggota/data', [AnggotaController::class, 'data'])->name('anggota.data'); 
    Route::post('/anggota/save', [AnggotaController::class, 'save'])->name('anggota.save'); 
});
