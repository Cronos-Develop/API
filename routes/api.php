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


Route::controller(UsuarioController::class)->prefix('users/')->group( function () {
    // Define um grupo de rotas para o recurso 'Usuario'
    // Todas as rotas aqui tem o prefixo 'users/' adicionado antes de serem processadas
    // As rotas autocamticamente chamam metodos na UsuarioController

    // Define uma rota GET para retornar dados do usuário a partir do id (hash)
    Route::get('/{hash}', 'index');

    // Define uma rota GET para exibir um usuário específico
    Route::get('/{user}/{hash}', 'show');

    // Define uma rota GET para recuperação de senha a partir do CPF/CNPJ do usuário - Operação feita no método recover()
    Route::get('/recuperar/{cpf}', 'recover');

    // Define uma rota POST para criar um novo usuário
    Route::post('/{hash}', 'store');

    // Rota PUT para atualizar um usuário específico.
    Route::put('/{user}/{hash}', 'update');

    // Rota DELETE para excluir um usuário específico.
    Route::delete('/{user}/{hash}', 'destroy');
});

Route::controller(EmpresaController::class)->prefix('empresas/')->group(function () {
    // Define um grupo de rotas para o recurso 'Empresa'
    // Todas as rotas aqui tem o prefixo 'empresas/' adicionado antes de serem processadas
    // As rotas autocamticamente chamam metodos na EmpresaController

    // Define uma rota GET para listar todas as empresas
    Route::get('{hash}', 'index');

    // Define uma rota GET para listar todas as empresas de um usuario
    Route::get('user/{hash}', 'userCompanies');

    // Define uma rota GET para listar todas as tarefas e subtarefas a partir de uma empresa
    Route::get('{empresa}/tarefas/{hash}', 'companieTasks');

    // Define uma rota GET para retornar lista de empresas a partir da id do usuário parceiro
    Route::get('partner/{hash}', 'partnerCompanies');

    // Define uma rota GET para exibir uma empresa específica
    Route::get('{empresa}/{hash}', 'show');

    // Define uma rota POST para criar uma nova empresa
    Route::post('{hash}', 'store');

    // Rota PUT para atualizar uma empresa específica
    Route::put('{empresa}/{hash}', 'update');

    // Rota DELETE para excluir uma empresa específica
    Route::delete('{empresa}/{hash}', 'destroy');
});
