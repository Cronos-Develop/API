<?php

namespace App\Extensions;

class CustomHasher
{
    public static function hashId($key){
        /**
         * Id: O id do usuário será um hash gerado a partir de seu CPF.
         * Primeiro, o CPF é dividido em 3 partes;
         * Depois, o primeiro dígito das 3 partes recebe uma letra (maiúscula ou minúscula, aleatoriamente);
         * O segundo dígito das 3 partes recebe um número convertido da tabela ASCII;
         * Por fim, o terceiro dígito recebe o valor de algum caracter especial da lista definida na classe CustomHasher;
         *
         * @param string $key A chave que será utilizada para gerar o hash
         * @return string $hash Retorna o hash gerado pela função
         **/

        // Dividir o CPF em partes
        $partes = str_split($key, 3);

        $hash = '';
        
        foreach ($partes as $parte) {
            // Gerar um número aleatório entre 0 e 1
            $randomCase = rand(0, 1);

            // Pegar o primeiro dígito e transformar em uma letra, com a escolha entre maiúscula e minúscula de acordo com o numero aleatório gerado
            $letra = ($randomCase == 0) ? chr(intval($parte[0]) + 97) : chr(intval($parte[0]) + 65);
            
            // Pegar o segundo dígito e converter para seu valor ASCII
            $numero = isset($parte[1]) ? ord($parte[1]) : ''; // Verifica se o segundo dígito está definido

            // Pegar o terceiro dígito e transformar em um número
            $numeroEspecial = isset($parte[2]) ? intval($parte[2]) + rand(0, 100) : 0;

            // Concatenar os resultados no hash
            $hash .= $letra . $numero . $numeroEspecial;
        }

        return $hash;
    }

}
