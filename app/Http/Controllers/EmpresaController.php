<?php

namespace App\Http\Controllers;

use App\Models\Gut;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Empresa;
use App\Models\Subtarefa;
use App\Models\T5w2h;
use App\Models\Usuario;
use Illuminate\Support\Fluent;
use App\Extensions\CPFValidator;

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

    function addPartnerCompanie(Empresa $empresa, string $usuario_cpf_cnpj, Usuario $hash)
    {
        /**
         * Adiciona o usuario recebido como parametro à lista de usuario parceiros de uma empresa.
         */

        $cpf_cnpj = CPFValidator::formatarCpfOuCnpj($usuario_cpf_cnpj);

        $usuario = DB::table('usuarios')->where('cpf_cnpj', $cpf_cnpj)->get()->first();

        if ($usuario) {
            $empresa->usuariosParceiros()->attach($usuario->id);
            return response()->json(['success' => 'Parceiro adicionado com sucesso']);
        }
        return response()->json(['error' => 'Houve algum erro']);
    }

    function removePartnerCompanie(Empresa $empresa, Usuario $usuario, Usuario $hash)
    {
        /**
         * Remove o usuario recebido como parametro a lista de usuario parceiros de uma empresa.
         */

        $empresa->usuariosParceiros()->detach($usuario->id);
        return response()->json(['success' => 'Parceiro deletado com sucesso']);
    }

    function storeT5w2h(Request $request, Empresa $empresa, Usuario $hash)
    {


        $contents = $request->all();

        $validator = Validator::make($contents, [
            "tarefa" => "nullable|string",
            "gut" => "nullable",
            "respostas.*.pergunta_id" => "required|int",
            "respostas.*.resposta" => "required|string",
        ]);

        $validator = $validator->sometimes(['gut.gravidade', 'gut.urgencia', 'gut.tendencia'], 'required', function (Fluent $input) {
            return isset($input->gut);
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $tarefa = Tarefa::create(["descrição" => $contents["tarefa"]]);
        if ($request['gut'])
            $gut = Gut::firstOrCreate($request['gut']);

        foreach ($contents["respostas"] as $resposta) {
            $t5w2h = T5w2h::updateOrCreate(["empresa_id" => $empresa->id, "pergunta_id" => $resposta["pergunta_id"], "tarefa_id" => $tarefa->id], ["resposta" => $resposta['resposta']]);
            if ($request['gut']) {
                $t5w2h->gut()->associate($gut);
                $t5w2h->save();
            }
        }
        return response()->json(['success' => 'Registros feitos com sucesso', "tarefa_id" => $tarefa->id], 201);
    }

    function updateT5w2h(Request $request, Empresa $empresa, Usuario $hash)
    {


        $contents = $request->all();

        $validator = Validator::make($contents, [
            "tarefa_id" => "required|int",
            "tarefa" => "nullable",
            "gut" => "nullable",
            "respostas.*.pergunta_id" => "required|int",
            "respostas.*.resposta" => "required|string",
        ]);

        $validator = $validator->sometimes(['gut.gravidade', 'gut.urgencia', 'gut.tendencia'], 'required', function (Fluent $input) {
            return isset($input->gut);
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $contents = $validator->validated();
        $tarefa = Tarefa::find($contents['tarefa_id']);
        if (isset($contents['tarefa'])) {
            $tarefa->descrição = $contents['tarefa'];
            $tarefa->save();
        }
        $t5w2hs = $tarefa->t5w2hs;
        if (isset($contents['gut'])) {
            $gut = Gut::firstOrCreate($contents['gut']);
            $t5w2hs->each(function (T5w2h $t5w2h) use ($gut) {
                $t5w2h->gut()->associate($gut);
            });
        }

        $gutId = $t5w2hs->first()->gut->id;
        if (isset($contents['respostas'])) {
            foreach ($contents['respostas'] as $resposta) {
                // $t5w2hs->where('pergunta_id', $resposta['pergunta_id'])->first()->update(['resposta' => $resposta['resposta']]);
                T5w2h::updateOrCreate(['empresa_id' => $empresa->id, 'gut_id' => $gutId, 'pergunta_id' => $resposta['pergunta_id'], 'tarefa_id' => $contents['tarefa_id']], ['resposta' => $resposta['resposta']]);
            }
        }


        // $tarefa = Tarefa::firstOrCreate(["descrição"=> $contents["tarefa"]]);
        // if ($request['gut'])
        //     $gut = Gut::firstOrCreate($request['gut']);
        //
        // foreach ($contents["respostas"] as $resposta) {
        //     $t5w2h = T5w2h::updateOrCreate(["empresa_id" => $empresa->id, "pergunta_id" => $resposta["pergunta_id"], "tarefa_id" => $tarefa->id], ["resposta" => $resposta['resposta']]);
        //     if ($request['gut'])
        //     {
        //         $t5w2h->gut()->associate($gut);
        //         $t5w2h->save();
        //     }
        // }
        return response()->json(['success' => 'Registros feitos com sucesso' . $gutId], 201);
    }



    function companieTasks(Empresa $empresa, Usuario $hash)
    {

        //retornar tarefas e subtarefas da empresa recebida como parametro.
        $tarefas = $empresa->t5w2hs()->distinct()->get(['tarefa_id']);
        return $tarefas->load('tarefa.subtarefas:id,tarefa_id,subtarefa');
    }

    function updateSubtasks(Request $request, Subtarefa $subtarefa)
    {
        $validator = Validator::make($request->all(), [
            'subtarefa' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $value = $validator->validate()['subtarefa'];
        $subtarefa->subtarefa = $value;
        $subtarefa->save();
        return response()->json(['success' => "Subtarefa atualizada com sucesso"], 201);
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
        return response()->json(['success' => $empresa], 200);
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

    public function addSubtasks(Request $request, Tarefa $tarefa, Usuario $hash)
    {
        $validator = Validator::make($request->all(), [
            '*' => 'string'
        ]);

        // Verifica se a validação falhou
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $data = $validator->validated();

        foreach ($data as $task) {
            $tarefa->subtarefas()->create(['usuario_id' => $hash->id, 'subtarefa' => $task]);
        }
        return response()->json(['success' => 'Subtarefas registradas com sucesso'], 201);
    }

    public function removeSubtask(Subtarefa $subtarefa, Usuario $hash)
    {
        Subtarefa::where('id', $subtarefa->id)->delete();
        return response()->json(['success' => 'Subtarefa deletada com sucesso'], 201);
    }

    public function patchTasks(Tarefa $tarefa, Usuario $hash)
    {
        $tarefa->feito = !$tarefa->feito;
        $tarefa->save();
        $message = $tarefa->feito ? "feita" : "não feita";
        return response()->json(['success' => "Tarefa marcada como $message"], 201);
    }

    public function patchSubtask(Subtarefa $subtarefa, Usuario $hash)
    {
        $subtarefa->feito = !$subtarefa->feito;
        $subtarefa->save();
        $message = $subtarefa->feito ? "feita" : "não feita";
        return response()->json(['success' => "Subtarefa marcada como $message"], 201);
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
            'tarefa:id,descrição',
            'pergunta:id,pergunta',
            'gut:id,gravidade,urgencia,tendencia'
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
