<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Gut;
use App\Models\T5w2h;
use App\Models\Usuario;
use Illuminate\Http\Request;

class GutController extends Controller
{
    /**
    * Armazena uma nova GUT.
    * Retorna um erro 404 automaticamente se a empresa ou o usuario não forem encontrados.
    * @param Illuminate\Http\Request $request
    * @param App\Models\Empresa $empresa
    * @param App\Models\Usuario $hash
    */
    function store(Request $request, Empresa $empresa, Usuario $hash)
    {
        $t5w2hs = $empresa->t5w2hs;
        $gutArray = array();
        //Coleta o conteudo do $request e armazena num array de Guts
        for ($i = 0; $i < count($request->all()); $i++) {
            $gut = new Gut(
                [
                    'gravidade' => $request->input($i . ".gut.0"),
                    'urgencia' => $request->input($i . ".gut.1"),
                    'tendencia' => $request->input($i . ".gut.2")
                ]
            );
            $gutArray[$i]['gut'] = $gut;
            $gutArray[$i]['total'] = $gut->gravidade * $gut->urgencia * $gut->tendencia;
        }

        // Ordena o array com base no total de pontos
        usort($gutArray, function ($a, $b) {
            if ($a['total'] == $b['total']) {
                return 0;
            }
            return ($a['total'] > $b['total']) ? -1 : 1;
        });

        //Insere cada gut associando com sua respectiva linha na tabela 5w2h
        for ($i = 0; $i < count($request->all()); $i++) {
            $t5w2hs->where('pergunta_id', $request->input($i . ".pergunta_id"))
                ->first()
                ->gut()
                ->save($gutArray[$i]['gut']);
        }


        return response()->json(['sucesso' => 'Gut cadastrado com sucesso'], 200);
    }
}
