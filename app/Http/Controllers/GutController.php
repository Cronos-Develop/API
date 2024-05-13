<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Gut;
use App\Models\T5w2h;
use App\Models\Usuario;
use Illuminate\Http\Request;

class GutController extends Controller
{
    function store(Request $request, Empresa $empresa, Usuario $hash)
    {
        $t5w2hs = $empresa->t5w2hs;
        for ($i = 0; $i < count($request->all()); $i++) {
            $gut = new Gut(
                [
                    'gravidade' => $request->input($i . ".gut.0"),
                    'urgencia' => $request->input($i . ".gut.1"),
                    'tendencia' => $request->input($i . ".gut.2")
                ]
            );
            $t5w2hs->where('pergunta_id', $request->input($i . ".pergunta_id"))
                ->first()
                ->gut()
                ->save($gut);
        }

        return response()->json(['sucesso' => 'Gut cadastrada com sucesso'], 200);
    }
}
