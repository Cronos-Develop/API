<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('home');
});

Route::controller(LoginController::class)->group(function(){
    Route::get('/login', 'index')->name('login.index');
    Route::post('/login', 'store')->name('login.store');
});

Route::get('/api', [HomeController::class, 'index'])->name('home');
Route::post('api/user', [UserController::class, 'show'])->name('user.show');
//Route::get('/api/users/{user}', [UserController::class, 'show'])->name('users.show');
Route::post('/api/users', [UserController::class, 'store'])->name('users.store');
