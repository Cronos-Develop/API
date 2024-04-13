<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;

Route::get('/api', function () {
    return view('login');
});

Route::post('/api/login', [LoginController::class, 'index'])->name('login.index');


