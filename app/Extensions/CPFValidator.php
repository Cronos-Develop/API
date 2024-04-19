<?php

namespace App\Extensions;

class CPFValidator
{
    public static function validarCPF($cpf)
    {
        // Remove todos os caracteres que não sejam números
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Validação do CPF de acordo com o padrão brasileiro
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
}
