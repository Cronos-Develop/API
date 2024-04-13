<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Importa a classe Request para lidar com solicitações HTTP

class RegisterController extends Controller
{
    // Método para exibir o formulário de registro
    public function index(Request $request){
        return view('register'); // Retorna a view 'register'
    }

    // Método para armazenar os dados do novo usuário
    public function store(Request $request){
        // Valida os dados da solicitação
        $validatedData = $request->validate([
            'name' => 'required', // O campo 'name' é obrigatório
            'email' => 'required|email', // O campo 'email' é obrigatório e deve ser um endereço de e-mail válido
            'password' => 'required' // O campo 'password' é obrigatório
        ]);
        
        // Obtém os dados do nome, e-mail e senha do usuário da solicitação
        $userName = $request->name;
        $userEmail = $request->email;
        $password = $request->password;
    }
}
