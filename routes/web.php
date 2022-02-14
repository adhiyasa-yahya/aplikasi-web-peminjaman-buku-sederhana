<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/', function () {
    return redirect()->route('login');;
});

Route::group(['prefix' => 'dashboard', 'middleware' => ['web', 'auth']], function() {
    Route::get('/', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::group(['prefix' => 'master'],function() {
        Route::get('/book', function () {
            return view('dashboard.master.books.index');
        })->name('book');
        Route::get('/book/type', function () {
            return view('dashboard.master.books.jenis_buku');
        })->name('book.type');
    });
    
    Route::group(['prefix' => 'anggota'],function() {
        Route::get('/', function () {
            return view('dashboard.master.anggota.index');
        })->name('anggota');
    });

    Route::group(['prefix' => 'peminjaman'],function() {
        Route::get('/', function () {
            return view('dashboard.peminjaman.peminjaman-buku');
        })->name('peminjaman');

        Route::get('/histori', function () {
            return view('dashboard.peminjaman.histori-peminjaman');
        })->name('histori');
    });
});
