<?php

namespace App\Services;

use Carbon\Carbon;
use App\Services\DividirApellidoNombresService;

class FormatearExcelPadresService
{

    private $dividirApellidoNombresService;

    public function __invoke($hojas)
    {
        return $this->formatear($hojas);
    }

    /**
     * Formatear datos de apoderados
     * @param array $hojas [hoja => [fila => [columna => valor]]]
     * @return array [
     *      codigo_modular => valor,
     *      modalidad => valor,
     *      nivel => valor,
     *      turno => valor,
     *      estudiantes => [
     *          ...,
     *          apoderado => [...]
     * ]
     */
    public static function formatear($hojas)
    {
        if (count($hojas) == 0) {
            return [];
        }

        $estudiantes = [];
        $filas = $hojas[0];

        $data['codigo_modular'] = trim($filas[8]['M']);
        $data['modalidad'] = trim($filas[8]['S']);
        $data['nivel'] = trim($filas[8]['U']);
        $data['turno'] = trim($filas[8]['V']);

        // Reemplazar texto a códigos identificadores
        $sexos = [
            'HOMBRE' => 'M',
            'MUJER' => 'F',
            'H' => 'M',
            'F' => 'F',
        ];
        $tipos_docs = [
            '1' => 'DNI',
            '2' => 'RUC',
            '3' => 'CE',
        ];

        foreach ($filas as $index_fila => $fila) {
            // Extraer datos a partir de la fila 13
            if ($index_fila >= 13) {
                // Ignorar filas sin codigo de estudiante
                if (trim($fila['H']) == '') {
                    continue;
                }
                // Limpiar datos de la fila
                $fila = array_map('trim', $fila);

                // Formatear datos de estudiantes y padres en nuevo array
                $estudiantes[] = array(
                    'grado' => $fila['C'],
                    'seccion' => $fila['D'],
                    'cod_tipo_documento' => array_search(strtoupper($fila['E']), $tipos_docs) ?: '0',
                    'documento' => trim($fila['F']),
                    'validado_reniec' => $fila['G'],
                    'codigo_estudiante' => $fila['H'],
                    'paterno' => $fila['I'],
                    'materno' => $fila['J'],
                    'nombres' => $fila['K'],
                    'sexo' => $sexos[strtoupper($fila['L'])],
                    'nacimiento' => Carbon::createFromFormat('d/m/Y', $fila['N'])->format('Y-m-d'),
                    'estado_matricula' => $fila['R'],

                    'apo_paterno' => DividirApellidoNombresService::dividir($fila['AS'])['paterno'],
                    'apo_materno' => DividirApellidoNombresService::dividir($fila['AS'])['materno'],
                    'apo_nombres' => DividirApellidoNombresService::dividir($fila['AS'])['nombres'],
                    'apo_sexo' => $sexos[strtoupper($fila['AT'])],
                    'apo_parentesco' => trim($fila['AU']),
                    'apo_cod_tipo_documento' =>  array_search(strtoupper($fila['AV']), $tipos_docs) ?: '0',
                    'apo_documento' => trim($fila['AW']),
                    'apo_validado_reniec' => trim($fila['AX']),
                    'apo_correo' => trim(explode('/', $fila['AY'])[0]),
                    'apo_celular' => trim(explode('/', $fila['AZ'])[0]),
                );
            }
        }

        $data['estudiantes'] = $estudiantes;

        return $data;
    }
}
