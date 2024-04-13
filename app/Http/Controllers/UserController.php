<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request){
        //echo $request;

        $validatedData = $request->validate([
            'email' => 'required',
            'senha' => 'required',
        ]);

        $user = User::where('email', $validatedData['email'])
                    ->where('senha', $validatedData['senha'])
                    ->first();

        if ($user) {
            // Se o usuário for encontrado, retorne os detalhes do usuário
            return response()->json(['user_id' => $user->id]);
        } else {
            // Caso contrário, retorne uma mensagem de erro
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }
    }

    public function store(Request $request)
    {
        // Valide os dados de cadastro do usuário
        $validatedData = $request->validate([
            'login' => 'required|unique:users',
            'senha' => 'required',
            // Adicione outras regras de validação conforme necessário
        ]);

        // Crie um novo usuário
        $user = User::create([
            'login' => $validatedData['login'],
            'senha' => $validatedData['senha'],
            // Adicione outros campos conforme necessário
        ]);

        // Retorne os detalhes do usuário recém-criado
        return response()->json($user, 201);
    }
}
