<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Importa a classe Hash para trabalhar com hash de senhas
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request){
        return DB::table('users')->get();
    }

    public function show(string $userData){
        // Supondo que os dados (email e senha) venham no formato email:senha
        $userDataArray = explode(':', $userData);

        // Obtém o e-mail e a senha da solicitação
        $userEmail = $userDataArray[0];
        $password = $userDataArray[1];

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

    public function store(Request $request)
    {
        
    }
}
