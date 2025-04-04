<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class DividirApellidoNombresService
{
    /**
     * Dividir apellidos y nombres combinados
     * @param string $apellidos_nombres Apellidos y nombres combinados
     * @return array Contiene apellido paterno, materno y nombres
     */
    public function __invoke($apellidos_nombres)
    {
        return $this->dividir($apellidos_nombres);
    }

    /**
     * Dividir apellidos y nombres combinados
     * @param string $apellidos_nombres Apellidos y nombres combinados
     * @return array Contiene primer apellido, segundo apellido y nombres
     */
    public static function dividir($apellidos_nombres)
    {
        // Poner todo en mayúsculas
        $apenom = strtoupper($apellidos_nombres);

        // Reemplazar apellido en blanco con asterisco
        $apenom = str_replace("  ", " * ", $apenom);
        $apenom = str_replace(" - ", " * ", $apenom);

        $partes = explode(" ", $apenom);

        $parte_usable = [];
        foreach ( $partes as $index => $parte ) {
            if (!in_array($parte, ['DE', 'LA', 'DEL', 'LAS', 'LOS', 'DI', 'MC'. 'Y'])) {
                $parte_usable[] = $index;
            }
        }

        $partes_usables = count($parte_usable);

        switch( $partes_usables ) {
            case 1:
                // Asumir solo 1 nombre
                $datos = [
                    'paterno' => null,
                    'materno' => null,
                    'nombres' => implode(" ", $partes),
                    ];
                break;
            case 2:
                // Asumir 1 apellido y 1 nombre
                $datos = [
                    'paterno' => implode(" ", array_slice($partes, 0, $parte_usable[0] + 1)),
                    'materno' => null,
                    'nombres' => implode(" ", array_slice($partes, $parte_usable[0] + 1)),
                ];
                break;
            default:
                if( in_array('*', $partes) ) {
                    // Asumir segundo apellido en blanco
                    $datos = [
                        'paterno' => implode(" ", array_slice($partes, 0, $parte_usable[0] + 1)),
                        'materno' => null,
                        'nombres' => implode(" ", array_slice($partes, $parte_usable[1] + 1)),
                    ];
                } else {
                    // Asumir 2 apellidos y 1 ó mas nombres
                    $datos = [
                        'paterno' => implode(" ", array_slice($partes, 0, $parte_usable[0] + 1)),
                        'materno' => implode(" ", array_slice($partes, $parte_usable[0] + 1, $parte_usable[1] - $parte_usable[0])),
                        'nombres' => implode(" ", array_slice($partes, $parte_usable[1] + 1)),
                    ];
                }
        }
        return $datos;
    }
}