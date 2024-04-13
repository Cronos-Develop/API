<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Models\User;

Route::get('/api', function () {
    return view('welcome');
});

/*Route::get('/api/login', [LoginController::class, 'index'])->name('login.index');
Route::post('/api/login', [LoginController::class, 'show'])->name('login.show');


Route::get('/api/register', [RegisterController::class, 'index'])->name('register.index');
Route::post('/api/register', [RegisterController::class, 'store'])->name('register.store');*/

Route::group([], function() {
    Route::get('/api/users', [UserController::class, 'index']);
    Route::get('/api/users/{user}', [UserController::class, 'show']);
});
