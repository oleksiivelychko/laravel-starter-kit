<?php

use App\Http\Controllers\JwtController;
use Illuminate\Support\Facades\Route;

Route::post('login', [JwtController::class, 'login'])->name('jwt.login');
Route::post('register', [JwtController::class, 'register'])->name('jwt.register');
Route::post('logout', [JwtController::class, 'logout'])->name('jwt.logout');
Route::get('refresh', [JwtController::class, 'refresh'])->name('jwt.refresh');
Route::get('me', [JwtController::class, 'me'])->name('jwt.me');
