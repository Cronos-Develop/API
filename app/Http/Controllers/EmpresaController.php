<?php

namespace App\Http\Controllers;

use App\Models\Gut;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Empresa;
use App\Models\T5w2h;
use App\Models\Usuario;

class EmpresaController extends Controller
{
    public function index(Request $request)
    {
        /**
         * Retorna todos os registros da tabela 'empresas' do banco de dados.
         *
         * @param  \Illuminate\Http\Request  $request  A requisição HTTP.
         * @return void
         */

        // Retorna todos os registros da tabela 'empresas' do banco de dados usando o facade DB do Laravel
        return DB::table('empresas')->get();  // Caso a função venha a ser usada novamente, basta descomentar
    }

    public function userCompanies(Usuario $hash) //Laravel converte a chave primaria recebida no Usuario correspondente automacimente.
    {
        /**
         * Retorna todos os registros da tabela 'empresas' que tem 'usuario_id' igual a $hash.
         */

        return $hash->empresas;
    }

    function partnerCompanies(Usuario $hash)
    { //Laravel automaticamente converte a chave primaria recebida no objeto Usuario correspondente.
        /**
         * Retorna todos os registros da tabela 'empresas' que tem Usuario recebido como parceiro.
         * Devolve um erro 404 ao cliente se o usuario não for encontrado.
         *
         * @param  \App\Models\Usuario  $hash parceiro das empresas.
         */
        return $hash->empresasParceiras;
    }

    function storeT5w2h(Request $request, Empresa $empresa, Usuario $hash)
    {


        $contents = $request->all();

        $validator = Validator::make($contents, [
            "tarefa" => "required|string",
            "gut.gravidade"=> "required",
            "gut.urgencia"=> "required",
            "gut.tendencia"=> "required",
            "respostas.*.pergunta_id" => "required|int",
            "respostas.*.resposta" => "required|string",
        ]);

        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $tarefa = Tarefa::firstOrCreate(["descrição"=> $contents["tarefa"]]);
        $gut = Gut::firstOrCreate($request['gut']);

        foreach ($contents["respostas"] as $resposta) {
            $t5w2h = T5w2h::updateOrCreate(["empresa_id" => $empresa->id, "pergunta_id" => $resposta["pergunta_id"], "tarefa_id" => $tarefa->id], ["resposta" => $resposta['resposta']]);
            $t5w2h->gut()->associate($gut);
            $t5w2h->save();
        }
        return response()->json(['success' => 'Registros feitos com sucesso', "tarefa_id" => $tarefa->id], 201);
    }

    function companieTasks(Empresa $empresa, Usuario $hash)
    {

        //retornar tarefas e subtarefas da empresa recebida como parametro.
        $tarefas = $empresa->t5w2hs()->distinct()->get(['tarefa_id']);
        return $tarefas->load('tarefa.subtarefas:tarefa_id,subtarefa');

    }

    public function show(string $empresaId, string $userHash)
    {
        /**
         * Retorna os detalhes de uma empresa com base no ID fornecido.
         *
         * @param  string  $empresaId  O ID da empresa a ser buscada.
         * @param  string  $userHash  Um hash usado para verificar as permissões do usuário.
         * @return \Illuminate\Http\JsonResponse  Uma resposta JSON com os detalhes da empresa ou uma mensagem de erro.
         */

        // Busca a empresa no banco de dados pelo ID fornecido
        $empresa = DB::table('empresas')->where('id', $empresaId)->get()->first();

        // Verifica se a empresa foi encontrada
        if (!$empresa) {
            return response()->json(['error' => 'Empresa não encontrada'], 404);
        }

        // Retorna os detalhes da empresa em formato JSON
        return response()->json(['success' => $empresa->nome_da_empresa], 200);
    }

    public function store(Request $request, string $userHash)
    {
        /**
         * Armazena uma nova empresa com base nos dados fornecidos.
         *
         * @param  \Illuminate\Http\Request  $request  A requisição HTTP contendo os dados da empresa a ser armazenada.
         * @param  string  $userHash  Um hash usado para verificar as permissões do usuário.
         * @return \Illuminate\Http\JsonResponse  Uma resposta JSON indicando sucesso ou falha ao registrar a empresa.
         */

        // Valida os dados da requisição
        $validator = Validator::make($request->all(), [
            'usuario_id' => 'required',
            'nome_da_empresa' => 'required',
            'nicho' => 'required',
            'resumo' => 'required'
        ]);

        // Verifica se a validação falhou
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Verifica se já existe uma empresa cadastrada com o nome da empresa que será cadastrada
        if (DB::table('empresas')->where('nome_da_empresa', $request->input('nome_da_empresa'))->get()->first()) {
            return response()->json(['errors' => 'Já existe um registro de empresa com esse nome'], 422);
        }

        // Cria uma nova empresa com os dados fornecidos
        $created = Empresa::create([
            'usuario_id' => $request->input('usuario_id'),
            'nome_da_empresa' => $request->input('nome_da_empresa'),
            'nicho' => $request->input('nicho'),
            'resumo' => $request->input('resumo')
        ]);

        // Verifica se a empresa foi criada com sucesso e retorna uma resposta JSON adequada
        if ($created) {
            return response()->json(['success' => 'Empresa registrada com sucesso'], 201);
        }
        return response()->json(['errors' => 'Houve algum erro ao registrar empresa'], 422);
    }

    public function update(Request $request, string $empresaId, string $userHash)
    {
        /**
         * Atualiza os dados de uma empresa existente com base nos dados fornecidos.
         *
         * @param  \Illuminate\Http\Request  $request  A requisição HTTP contendo os dados da empresa a ser atualizada.
         * @param  string  $empresaId  O ID da empresa a ser atualizada.
         * @param  string  $userHash  Um hash usado para verificar as permissões do usuário.
         * @return \Illuminate\Http\JsonResponse  Uma resposta JSON indicando sucesso ou falha ao atualizar os dados da empresa.
         */

        // Valida os dados da requisição
        $validator = Validator::make($request->all(), [
            'usuario_id' => 'required',
            'nome_da_empresa' => 'required',
            'nicho' => 'required',
            'resumo' => 'required'
        ]);

        // Verifica se a validação falhou
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Obtém os dados validados da requisição
        $validated = $validator->validated();

        // Atualiza os dados da empresa com o ID fornecido
        $updated = Empresa::find($empresaId)->update([
            'usuario_id' => $validated['usuario_id'],
            'nome_da_empresa' => $validated['nome_da_empresa'],
            'nicho' => $validated['nicho'],
            'resumo' => $validated['resumo']
        ]);

        // Verifica se a empresa foi atualizada com sucesso e retorna uma resposta JSON adequada
        if ($updated) {
            return response()->json(['success' => 'Dados atualizados com sucesso'], 200);
        }
        return response()->json(['errors' => 'Erro ao atualizar registro'], 400);
    }

    public function destroyT5w2h(Tarefa $tarefa, Usuario $usuario)
    {
        $tarefa->t5w2hs()->delete();
        return response()->json(['success' => 'Dados deletados com sucesso'], 200);
    }

    public function destroy(string $empresaId, string $userHash)
    {
        /**
         * Deleta uma empresa com base no ID fornecido.
         *
         * @param  string  $empresaId  O ID da empresa a ser deletada.
         * @param  string  $userHash  Um hash usado para verificar as permissões do usuário.
         * @return \Illuminate\Http\JsonResponse  Uma resposta JSON indicando sucesso ou falha ao deletar a empresa.
         */

        // Deleta a empresa do banco de dados com o ID fornecido
        $deleted = DB::table('empresas')->where('id', $empresaId)->delete();

        // Verifica se a empresa foi deletada com sucesso e retorna uma resposta JSON adequada
        if ($deleted) {
            return response()->json(['success' => 'Empresa deletada com sucesso'], 200);
        }
        return response()->json(['errors' => 'Erro ao deletar empresa'], 400);
    }

    public function showT5w2h(Empresa $empresa, Usuario $usuario)
    {
        return $empresa->t5w2hs()->with(
            'tarefa:id,descrição', 'pergunta:id,pergunta', 'gut:id,gravidade,urgencia,tendencia'
        )->get([
            'id',
            'empresa_id',
            'tarefa_id',
            'pergunta_id',
            'gut_id',
            'resposta'
        ]);
    }
}
