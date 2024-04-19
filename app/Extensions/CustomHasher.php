<?php

namespace App\Extensions;

class CustomHasher
{
    public static function hashId($key){
        $caracteresEspeciais = array('.', ',', '/', '\\', '*', '&', '%', '$', '#', '@', '!');

        // Dividir o CPF em partes
        $partes = str_split($key, 3);

        $hash = '';
        
        foreach ($partes as $parte) {
            // Gerar um número aleatório entre 0 e 1
            $randomCase = rand(0, 1);

            // Pegar o primeiro dígito e transformar em uma letra, com a escolha entre maiúscula e minúscula de acordo com o numero aleatório gerado
            $letra = ($randomCase == 0) ? chr($parte[0] + 97) : chr($parte[0] + 65);
            
            // Pegar o segundo dígito e converter para seu valor ASCII
            $numero = isset($parte[1]) ? ord($parte[1]) : ''; // Verifica se o segundo dígito está definido

            // Pegar o terceiro dígito e transformar em um caractere especial
            $indice = isset($parte[2]) ? $parte[2] % count($caracteresEspeciais) : 0; // Verifica se o terceiro dígito está definido
            $caractereEspecial = $caracteresEspeciais[$indice];

            // Concatenar os resultados no hash
            $hash .= $letra . $numero . $caractereEspecial;
        }

        return $hash;
    }

}
