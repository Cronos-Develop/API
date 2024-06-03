<?php

namespace App\Http\Controllers;

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

    public function ask(Request $request)
    {
        $chat = Gemini::chat()
            ->startChat(history: [
                Content::parse(part: 'Você é um grande e experiente empresario'),
                Content::parse(part: 'Suas respostas serão curtas e divididas por quebra de linha.'),
            ]);

        try {
            $response = $chat->sendMessage($request->input('prompt'));
            return response()->json(['data' => $response->text()], 200);
        } catch (\Throwable $th) {
            return response()->json(['data' => "não foi possivel responder seu prompt"], 400);
            
        }
        
        
    }
}
