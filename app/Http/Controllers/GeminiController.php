<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Exception;
use Gemini\Data\Content;
use Gemini\Enums\Role;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Http\Request;

class GeminiController extends Controller
{
    public function index()
    {
        $result = Gemini::geminiPro()->generateContent('Hello!');
        return response()->json(['data' => $result->text()], 201);
    }

    public function tasks(Request $request, Usuario $hash)
    {
        $chat = Gemini::chat()
            ->startChat(history: [
                // Content::parse(part: 'Suas respostas serão curtas'),
                Content::parse(part: "De uma sequencia de passos para realizar as  tarefas. Evite usar caracteres especias nas respostas"),
                Content::parse(part: "input: Usar a api do gemini"),
                Content::parse(part: 'output: [    "Criar uma conta Gemini.",    "Obter a API key e o segredo da API.",    "Use a biblioteca de cliente Gemini para Python ou outros recursos.",    "Autentique com a API key e o segredo.",    "Chame os endpoints da API para recuperar dados ou enviar ordens."]'),
                Content::parse(part: "input: Criar uma empresa"),
                Content::parse(part: 'output: [    "Defina o seu negócio e modelo",    "Escolha uma estrutura empresarial",    "Obtenha licenças e alvarás necessários",    "Abra uma conta bancária comercial",    "Registre seu nome comercial e marca registrada",    "Estabeleça um sistema de contabilidade",    "Obtenha seguro comercial",    "Contrate funcionários (se necessário)",    "Comercialize seu negócio"]'),  
            ]);

        try {
            $tarefa = $request->input('tarefa');
            if (empty($tarefa)) throw new Exception();
            $response = $chat->sendMessage("input:" . $tarefa . "\n output:");
            return response()->json(json_decode($response->text()), 200);
        } catch (\Throwable $th) {
            return response()->json(['data' => "não foi possivel responder seu prompt. Verifique o conteudo ou se o formato está correto"], 400);
            
        }
        
        
    }
}
