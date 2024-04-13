<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; // Importa a classe DB para interagir com o banco de dados
use App\Models\User; // Importa o modelo User
use Illuminate\Http\Request; // Importa a classe Request para lidar com solicitações HTTP
use Illuminate\Support\Facades\Hash; // Importa a classe Hash para trabalhar com hash de senhas

class LoginController extends Controller
{
    // Método para exibir o formulário de login
    public function index(Request $request){
        return view('login'); // Retorna a view 'login'
    }

    // Método para processar a solicitação de login
    public function show(Request $request){
        // Valida os dados da solicitação
        $validatedData = $request->validate([  
            'email' => 'required|email', // O campo 'email' é obrigatório e deve ser um endereço de e-mail válido
            'password' => 'required' // O campo 'password' é obrigatório
        ]);

        // Obtém o e-mail e a senha da solicitação
        $userEmail = $request['email'];
        $password = $request['password'];

        // Busca o usuário no banco de dados pelo e-mail fornecido
        $user = DB::table('users')->where('email', $userEmail)->get()->first();
        if (!$user){
            // Se o usuário não for encontrado, retorna um erro 404
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        // Obtém a senha criptografada do usuário do banco de dados
        $hashedPassword = $user->password;

        // Verifica se a senha fornecida corresponde à senha criptografada
        $correctPassword = Hash::check($request['password'], $hashedPassword);

        // Se o usuário for encontrado e a senha estiver correta, retorna o ID do usuário
        if ($user && $correctPassword){
            return response()->json(['id' => $user->id]);
        }
        else {
            // Se a senha estiver incorreta, retorna um erro 401
            return response()->json(['error' => 'Senha incorreta'], 401);
        }
    }
}
