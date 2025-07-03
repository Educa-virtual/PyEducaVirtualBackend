<?php

namespace App\Services;

class StringService
{
    // Recorta un texto a un número de caracteres específico
    static function recortarTexto($texto, $limite = 80, $sufijo = '...')
    {
        if (mb_strlen($texto) <= $limite) {
            return $texto;
        }
        return mb_strimwidth($texto, 0, $limite, $sufijo);
    }
}
