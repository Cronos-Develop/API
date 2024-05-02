<?php

namespace App\Extensions;

class CPFValidator
{
    
    public static function validarCpfOuCnpj($cpf_cnpj){
        /**
         *
         * A função validarCPF() verifica se um CPF é válido. 
         * Verifica se o número de caracteres está correto;
         * Verifia se os caracteres não são números iguais;
         * Verifica se a lógica por trás dos números é aceita; 
         *
         * @param string $cpf Recebe-se o CPF que queremos validar
         * @return string Retorna o próprio CPF caso seja válido
         * @return boolean Retorna false caso o CPF seja inválido
         **/

        // Remove todos os caracteres que não sejam números
        $cpf_cnpj = preg_replace('/[^0-9]/', '', $cpf_cnpj);

        // Verifica se é CPF
        if (strlen($cpf_cnpj) == 11) {
            return self::validarCPF($cpf_cnpj);
        }
        // Verifica se é CNPJ
        elseif (strlen($cpf_cnpj) == 14) {
            return self::validarCNPJ($cpf_cnpj);
        }

        // Se não é CPF nem CNPJ, retorna falso
        return false;
    }

    private static function validarCPF($cpf)
    {
        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Validação do CPF
        for ($i = 9; $i < 11; $i++) {
            for ($j = 0, $soma = 0; $j < $i; $j++) {
                $soma += $cpf[$j] * (($i + 1) - $j);
            }
            $resto = $soma % 11;
            if ($resto < 2) {
                $digito = 0;
            } else {
                $digito = 11 - $resto;
            }
            if ($digito != $cpf[$i]) {
                return false;
            }
        }
        return true;
    }

    private static function validarCNPJ($cnpj)
    {
         // Validação do CNPJ
         for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }

        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }
}
