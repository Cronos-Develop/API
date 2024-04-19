<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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


Route::group([], function() {
    // Define um grupo de rotas para o recurso 'users'

    // Define uma rota GET para listar todos os usuários
    Route::get('/users/{hash}', [UserController::class, 'index']); 

    // Define uma rota GET para exibir um usuário específico
    Route::get('/users/{user}/{hash}', [UserController::class, 'show']);

    // Define uma rota POST para criar um novo usuário
    Route::post('/users/{hash}', [UserController::class, 'store']);
});

