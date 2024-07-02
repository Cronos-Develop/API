<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Gut;
use App\Models\T5w2h;
use App\Models\Tarefa;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GutController extends Controller
{
    /**
    * Armazena uma nova GUT.
    * Retorna um erro 404 automaticamente se a empresa ou o usuario não forem encontrados.
    * @param Illuminate\Http\Request $request
    * @param App\Models\\Tarefa $tarefa
    * @param App\Models\Usuario $hash
    * @param App\Models\Empresa $empresa
    */
    function store(Request $request, Tarefa $tarefa, Usuario $hash)
    {

        $validator = Validator::make($request->all(), [
            "gravidade" => "required|int",
            "urgencia" => "required|int",
            "tendencia" => "required|int"
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $gut = Gut::firstOrCreate($validator->validated());
        $t5w2h = $tarefa->t5w2hs;
        $t5w2h->each(function (T5w2h $t5w2h) use ($gut) {
            $gut = $t5w2h->gut()->associate($gut);
            $gut->save();
        });

        return response()->json(['sucesso' => 'Gut cadastrado com sucesso'], 200);
    }
}
