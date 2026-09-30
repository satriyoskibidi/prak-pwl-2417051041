<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MataKuliahController;

Route::get('/matakuliah', [MataKuliahController::class, 'index'])->name('matakuliah.index');
Route::get('/matakuliah/create', [MataKuliahController::class, 'create'])->name('matakuliah.create');
Route::post('/matakuliah', [MataKuliahController::class, 'store'])->name('matakuliah.store');
Route::put('/matakuliah/{mk}', [MataKuliahController::class, 'update'])->name('matakuliah.update');
Route::delete('/matakuliah/{mk}', [MataKuliahController::class, 'destroy'])->name('matakuliah.destroy');
Route::get('/user', [UserController::class, 'index'])->name('user.index');
Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
Route::post('/user', [UserController::class, 'store'])->name('user.store');
Route::get('/user/export', [UserController::class, 'export'])->name('user.export');
Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
Route::get('/profile/{nama}/{npm}/{kelas}', [ProfileController::class, 'profile']);

Route::get('/', function () {
    return view('welcome');
});
