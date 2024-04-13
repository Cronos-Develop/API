<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

Route::get('/api', function () {
    return view('home');
});

Route::get('/api/login', [LoginController::class, 'index'])->name('login.index');
Route::post('/api/login', [LoginController::class, 'show'])->name('login.show');


Route::get('/api/register', [RegisterController::class, 'index'])->name('register.index');
Route::post('/api/register', [RegisterController::class, 'store'])->name('register.store');
