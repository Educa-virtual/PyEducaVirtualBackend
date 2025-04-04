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
        if( count($hojas) == 0 ) {
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
            '01' => 'DNI',
            '04' => 'CE',
            '06' => 'RUC',
            '00' => 'OT'];

        foreach($filas as $index_fila => $fila)
        {
            // Extraer datos a partir de la fila 13
            if($index_fila >= 13)
            {
                // Ignorar filas sin codigo de estudiante
                if ( trim($fila['H']) == '') {
                    continue;
                }
                // Limpiar datos de la fila
                $fila = array_map('trim', $fila);

                // Formatear datos de estudiantes y padres en nuevo array
                $estudiantes[] = array(
                    'grado' => $fila['C'],
                    'seccion' => $fila['D'],
                    'cod_tipo_documento' => array_search(strtoupper($fila['E']), $tipos_docs) ?: '00',
                    'documento' => $fila['F'],
                    'validado_reniec' => $fila['G'],
                    'codigo_estudiante' => $fila['H'],
                    'paterno' => $fila['I'],
                    'materno' => $fila['J'],
                    'nombres' => $fila['K'],
                    'sexo' => $sexos[strtoupper($fila['L'])],
                    'nacimiento' => Carbon::createFromFormat('d/m/Y', $fila['N'])->format('Y-m-d'),
                    'estado_matricula' => $fila['R'],
                    'apoderado' => array(
                        'apenom' => $fila['AS'],
                        'paterno' => DividirApellidoNombresService::dividir($fila['AS'])['paterno'],
                        'materno' => DividirApellidoNombresService::dividir($fila['AS'])['materno'],
                        'nombres' => DividirApellidoNombresService::dividir($fila['AS'])['nombres'],
                        'sexo' => $sexos[strtoupper($fila['AT'])],
                        'parentesco' => $fila['AU'],
                        'cod_tipo_documento' =>  array_search(strtoupper($fila['AV']), $tipos_docs) ?: '00',
                        'documento' => $fila['AW'],
                        'validado_reniec' => $fila['AX'],
                        'correo' => $fila['AY'],
                        'celular' => $fila['AZ'],
                    ),
                );
            }
        }

        $data['estudiantes'] = $estudiantes;
        
        return $data;
    }
}