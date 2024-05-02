<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Importa a classe Hash para trabalhar com hash de senhas
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Extensions\CPFValidator;
use App\Extensions\CustomHasher;

class UserController extends Controller
{
    public function index(Request $request){
        // Retorna todos os registros da tabela 'users' do banco de dados usando o facade DB do Laravel
        // return DB::table('users')->get();  // Caso a função venha a ser usada novamente, basta descomentar
    }

    public function show(string $userData, string $userHash){
        // Supondo que os dados (email e senha) venham no formato email:senha
        $userDataArray = explode(':', $userData);

        // Obtém o e-mail e a senha da solicitação
        $userEmail = $userDataArray[0];
        $password = $userDataArray[1];

        // Fazer verificação, a partir do hash, se o usuário logado tem permissão para acessar esse conteúdo

        // Busca o usuário no banco de dados pelo e-mail fornecido
        $user = DB::table('users')->where('email', $userEmail)->get()->first();
        if (!$user){
            // Se o usuário não for encontrado, retorna um erro 404
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        // Obtém a senha criptografada do usuário do banco de dados
        $hashedPassword = $user->password;

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

        $created = Usuario::create([
            'id' => $userId,
            'name' => $request->input('name'),
            'cpf_cnpj' => $request->input('cpf_cnpj'),
            'senha' => Hash::make($request->input('senha')),
            'email' => $request->input('email'),
            'telefone' => $request->input('telefone'),
            'endereco' => $request->input('endereco'),
            'cep' => $request->input('cep'),
            'nascimento' => $request->input('nascimento'),
            'empresario' => $request->input('empresario'),
            'nome_da_empresa' => $request->input('nome_da_empresa')
        ]);
        if ($created){
            return response()->json(['success' => 'Usuário registrado com sucesso'], 200);
        }
        return response()->json(['errors' => 'Houve algum erro ao registrar usuário'], 422); 
    }

    public function update(Request $request){
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
    }

}
