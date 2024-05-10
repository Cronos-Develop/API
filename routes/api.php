<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
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
    // Define um grupo de rotas para o recurso 'Usuario'

    // Define uma rota GET para retornar dados do usuário a partir do id (hash)
    Route::get('/users/{hash}', [UsuarioController::class, 'index']); 

    // Define uma rota GET para exibir um usuário específico
    Route::get('/users/{user}/{hash}', [UsuarioController::class, 'show']);

    // Define uma rota GET para recuperação de senha a partir do CPF/CNPJ do usuário - Operação feita no método recover()
    Route::get('/recuperar/{cpf}', [UsuarioController::class, 'recover']);

    // Define uma rota POST para criar um novo usuário
    Route::post('/users/{hash}', [UsuarioController::class, 'store']);

    // Rota PUT para atualizar um usuário específico.
    Route::put('/users/{user}/{hash}', [UsuarioController::class, 'update']);

    // Rota DELETE para excluir um usuário específico.
    Route::delete('/users/{user}/{hash}', [UsuarioController::class, 'destroy']);
});

Route::group([], function() {
    // Define um grupo de rotas para o recurso 'Empresa'

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

