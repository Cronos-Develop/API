<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmpresaController;

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

    // Rota PUT para atualizar um usuário específico.
    Route::put('/users/{user}/{hash}', [UserController::class, 'update']);

    // Rota DELETE para excluir um usuário específico.
    Route::delete('/users/{user}/{hash}', [UserController::class, 'destroy']);
});

Route::group([], function() {
    // Define um grupo de rotas para o recurso 'users'

    // Define uma rota GET para listar todas as empresas
    Route::get('/empresas/{hash}', [EmpresaController::class, 'index']); 

    // Define uma rota GET para exibir uma empresa específica
    Route::get('/empresas/{empresa}/{hash}', [EmpresaController::class, 'show']);

    // Define uma rota POST para criar uma nova empresa
    Route::post('/empresas/{hash}', [EmpresaController::class, 'store']);

    // Rota PUT para atualizar uma empresa específica
    Route::put('/empresas/{empresa}/{hash}', [EmpresaController::class, 'update']);

    // Rota DELETE para excluir uma empresa específica
    Route::delete('/empresas/{empresa}/{hash}', [EmpresaController::class, 'destroy']);
});

