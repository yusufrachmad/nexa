<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiController;

Route::get('/transaksi', [TransaksiController::class, 'index']);

Route::get('/transaksi/create', [TransaksiController::class, 'create']);

Route::post('/transaksi/store', [TransaksiController::class, 'store']);

Route::get('/transaksi/edit/{id}', [TransaksiController::class, 'edit']);

Route::put('/transaksi/edit/{id}', [TransaksiController::class, 'update']);

Route::delete('/transaksi/{id}', [TransaksiController::class, 'delete']);

Route::get('/transaksi/export', [TransaksiController::class, 'export']);
