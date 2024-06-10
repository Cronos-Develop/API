<?php

use App\Http\Controllers\GeminiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\GutController;

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


Route::controller(UsuarioController::class)->prefix('users/')->group(function () {
    // Define um grupo de rotas para o recurso 'Usuario'
    // Todas as rotas aqui têm o prefixo 'users/' adicionado a URI antes de serem processadas
    // As rotas autocamticamente chamam metodos na UsuarioController

    // Define uma rota GET para retornar dados do usuário a partir do id (hash)
    Route::get('', 'index');

    // Define uma rota GET para recuperação de senha a partir do CPF/CNPJ do usuário - Operação feita no método recover()
    Route::get('recuperar/{cpf}', 'sendRecoverEmail');

    // Define uma rota POST para troca de senha, redirecionada a partir do email de recuperação
    Route::post('trocarsenha/', 'recoverPassword');

    // Define uma rota GET para exibir um usuário específico
    Route::get('{user}/', 'show');

    // Define uma rota POST para criar um novo usuário
    Route::post('{hash}', 'store');

    // Rota PUT para atualizar um usuário específico.
    Route::put('{user}/{hash}', 'update');

    // Rota DELETE para excluir um usuário específico.
    Route::delete('{user}/{hash}', 'destroy');
});

Route::controller(EmpresaController::class)->prefix('empresas/')->group(function () {
    // Define um grupo de rotas para o recurso 'Empresa'
    // Todas as rotas aqui tem o prefixo 'empresas/' adicionado a URI antes de serem processadas
    // As rotas autocamticamente chamam metodos na EmpresaController

    // Define uma rota GET para listar todas as empresas
    Route::get('', 'index');

    // Define uma rota GET para listar todas as empresas de um usuario
    Route::get('user/{hash}', 'userCompanies');

    // Define um rota POST para fazer registros nas tabela 5w2h da empresa
    Route::post('t5w2h/{empresa}/{hash}', 'storeT5w2h');

    // Define uma rota GET para retornar lista de empresas a partir da id do usuário parceiro
    Route::get('partner/{hash}', 'partnerCompanies');

    // Define uma rota GET para exibir uma empresa específica
    Route::get('{empresa}/', 'show');

    // Define uma rota GET para listar todas as tarefas e subtarefas a partir de uma empresa
    Route::get('{empresa}/tarefas/', 'companieTasks');
    // Define um rota POST para fazer registros de tarefas para 5w2h
    Route::post('{empresa}/tarefas/{hash}', 'storeTasks');

    // Define uma rota POST para criar uma nova empresa
    Route::post('{hash}', 'store');

    // Rota PUT para atualizar uma empresa específica
    Route::put('{empresa}/{hash}', 'update');

    // Rota DELETE para excluir uma empresa específica
    Route::delete('{empresa}/{hash}', 'destroy');
});

Route::controller(GutController::class)->prefix('gut/')->group(function () {
    // Define um grupo de rotas para o recurso 'Gut'
    // Todas as rotas aqui tem o prefixo 'gut/' adicionado a URI antes de serem processadas
    // As rotas autocamticamente chamam métodos na GutController

    // Define uma rota POST para criar um novo gut
    Route::post('{empresa}/{hash}', 'store');

});


// IA

// Route::get('/gemini', [GeminiController::class, 'index']);
Route::post('/IA/{hash}', [GeminiController::class, 'tasks']);
