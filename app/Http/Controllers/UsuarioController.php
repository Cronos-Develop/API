<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Importa a classe Hash para trabalhar com hash de senhas
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Extensions\CPFValidator;
use App\Extensions\CustomHasher;

class UsuarioController extends Controller
{
    public function index(Request $request, string $userHash){
        /**
         * Armazena uma nova empresa com base nos dados fornecidos.
         *
         * @param  \Illuminate\Http\Request  $request  A requisição HTTP contendo o protocolo GET para receber dados de todos os usuários.
         * @param  string  $userHash  Um hash usado para verificar as permissões do usuário.
         * @return \Illuminate\Support\FacadesDB  Uma resposta DB Facades com todos os registros de empresas do banco de dados.
        */

        // Retorna todos os registros da tabela 'users' do banco de dados usando o facade DB do Laravel
        // return DB::table('users')->get();  // Caso a função venha a ser usada novamente, basta descomentar
    }

    public function show(string $userData, string $userHash){
        /**
         * Mostra o ID do usuário com base nos dados fornecidos.
         *
         * @param string $userData Uma string contendo o e-mail e a senha no formato email:senha.
         * @param string $userHash Um hash usado para verificar as permissões do usuário.
         * @return \Illuminate\Http\JsonResponse Uma resposta JSON contendo o ID do usuário se a autenticação for bem-sucedida, ou um erro se o usuário não for encontrado ou a senha estiver incorreta.
        */

        // Supondo que os dados (email e senha) venham no formato email:senha
        $userDataArray = explode(':', $userData);

        // Obtém o e-mail e a senha da solicitação
        $userEmail = $userDataArray[0];
        $password = $userDataArray[1];

        // Fazer verificação, a partir do hash, se o usuário logado tem permissão para acessar esse conteúdo

        // Busca o usuário no banco de dados pelo e-mail fornecido
        $user = DB::table('usuarios')->where('email', $userEmail)->get()->first();
        if (!$user){
            // Se o usuário não for encontrado, retorna um erro 404
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        // Obtém a senha criptografada do usuário do banco de dados
        $hashedPassword = $user->senha;

        // Verifica se a senha fornecida corresponde à senha criptografada
        $correctPassword = Hash::check($password, $hashedPassword);

        // Se o usuário for encontrado e a senha estiver correta, retorna o ID do usuário
        if ($user && $correctPassword){
            return response()->json(['id' => $user->id]);
        }
        else {
            // Se a senha estiver incorreta, retorna um erro 401
            return response()->json(['error' => 'Senha incorreta'], 401);
        }
    }

    public function store(Request $request, string $userHash){
        /**
         * Armazena um novo usuário com base nos dados fornecidos.
         *
         * @param \Illuminate\Http\Request $request A requisição HTTP contendo os dados do usuário a serem armazenados.
         * @param string $userHash Um hash usado para verificar as permissões do usuário.
         * @return \Illuminate\Http\JsonResponse Uma resposta JSON indicando sucesso ou falha ao registrar o usuário.
        */

        // Valida os dados da solicitação usando o Validator do Laravel
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'cpf_cnpj' => 'required',
            'senha' => 'required',
            'email' => 'required|email',
            'telefone' => 'required',
            'endereco' => 'required',
            'cep' => 'required',
            'nascimento' => 'required',
            'empresario' => 'required|numeric|between:0,1',
            'nome_da_empresa' => 'nullable'
        ]);

        // Verifica se a validação falhou
        if ($validator->fails()) {
            // Retorna uma resposta JSON com os erros de validação e o código de status 422 (Unprocessable Entity)
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // O CPF é validado na classe CPFValidator
        $cpf = CPFValidator::validarCpfOuCnpj($request->input('cpf_cnpj'));
        if (!$cpf){  //Caso o $cpf seja inválido, $cpf=false, então a mensagem abaixo é retornada
            return response()->json(['errors' => 'CPF ou CNPJ inválido'], 422);  
        }

        // Caso o CPF seja válido, seu valor é atribuído à variável $cpf, então criamos o Id a partir do CPF
        $cpf = $request->input('cpf_cnpj');
        $userId = CustomHasher::hashId($cpf);

        // Cria um novo registro de usuário usando o método estático 'create' do modelo Usuario.
        $created = Usuario::create([
            'id' => $userId,
            'name' => $request->input('name'),
            'cpf_cnpj' => $request->input('cpf_cnpj'),
            'senha' => Hash::make($request->input('senha')),  // Define a senha criptografada do usuário
            'email' => $request->input('email'),
            'telefone' => $request->input('telefone'),
            'endereco' => $request->input('endereco'),
            'cep' => $request->input('cep'),
            'nascimento' => $request->input('nascimento'),
            'empresario' => $request->input('empresario'),
            'nome_da_empresa' => $request->input('nome_da_empresa')
        ]);

        // Verifica se o usuário foi criado com sucesso.
        if ($created){
            // Se sim, retorna uma resposta JSON indicando sucesso (código 200).
            return response()->json(['success' => 'Usuário registrado com sucesso'], 201);
        }
        // Caso contrário, retorna uma resposta JSON indicando um erro (código 422).
        return response()->json(['errors' => 'Houve algum erro ao registrar usuário'], 422); 
    }

    public function update(Request $request, string $userId,string $userHash){
        /**
         * Atualiza os dados de um usuário existente com base nos dados fornecidos.
         *
         * @param \Illuminate\Http\Request $request A requisição HTTP contendo os dados do usuário a serem atualizados.
         * @param string $userId O ID do usuário a ser atualizado.
         * @param string $userHash Um hash usado para verificar as permissões do usuário.
         * @return \Illuminate\Http\JsonResponse Uma resposta JSON indicando sucesso ou falha ao atualizar os dados do usuário.
        */

        // Valida os dados recebidos na requisição usando o Validator do Laravel.
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'cpf_cnpj' => 'required',
            'senha' => 'required',
            'email' => 'required|email',
            'telefone' => 'required',
            'endereco' => 'required',
            'cep' => 'required',
            'nascimento' => 'required',
            'empresario' => 'required|numeric|between:0,1',
            'nome_da_empresa' => 'nullable'
        ]);

        // Verifica se houve falha na validação.
        if ($validator->fails()){
            // Retorna uma resposta JSON com os erros de validação e o código de status 422 (Unprocessable Entity)
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Recupera apenas os dados validados pela validação.
        $validated = $validator->validated();

        // Busca e atualiza os dados do usuário com o ID fornecido.
        $updated = Usuario::find($userId)->update([
            'name' => $validated['name'],
            'cpf_cnpj' => $validated['cpf_cnpj'],
            'senha' => Hash::make($validated['senha']),
            'email' => $validated['email'],
            'telefone' => $validated['telefone'],
            'endereco' => $validated['endereco'],
            'cep' => $validated['cep'],
            'nascimento' => $validated['nascimento'],
            'empresario' => $validated['empresario'],
            'nome_da_empresa' => $validated['nome_da_empresa']
        ]);

        // Verifica se a atualização foi bem-sucedida.
        if ($updated){
            // Retorna uma resposta JSON indicando sucesso (código 200).
            return response()->json(['success' => 'Dados atualizados com sucesso'], 200);
        }
        // Retorna uma resposta JSON indicando um erro (código 400) caso a atualização falhe.
        return response()->json(['errors' => 'Erro ao atualizar registro'], 400);
    }

    public function destroy(string $userId, string $userHash){
        /**
         * Exclui um usuário existente com base no ID fornecido.
         *
         * @param string $userId O ID do usuário a ser excluído.
         * @param string $userHash Um hash usado para verificar as permissões do usuário.
         * @return \Illuminate\Http\JsonResponse Uma resposta JSON indicando sucesso ou falha ao excluir o usuário.
        */

        // Exclui o usuário com o ID fornecido da tabela 'usuarios'.
        $deleted = DB::table('usuarios')->where('id', $userId)->delete();

        // Verifica se o usuário foi excluído com sucesso.
        if ($deleted){
            // Retorna uma resposta JSON indicando sucesso (código 200).
            return response()->json(['success' => 'Usuário deletado com sucesso'], 200);
        }
        // Retorna uma resposta JSON indicando um erro (código 400) caso a exclusão falhe.
        return response()->json(['errors' => 'Erro ao deletar usuário'], 400);
    }
}
