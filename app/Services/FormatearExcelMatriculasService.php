<?php

namespace App\Services;

use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FormatearExcelMatriculasService
{
    public function __invoke($hojas)
    {
        return $this->formatear($hojas);
    }

    /**
     * Formatear datos de matriculas
     * @param array $hojas [hoja => [fila => [columna => valor]]]
     * @return array [
     *      codigo_modular => valor,
     *      modalidad => valor,
     *      nivel => valor,
     *      turno => valor,
     *      estudiantes => [...]
     * ]
     */
    public static function formatear($hojas)
    {
        if( count($hojas) == 0 ) {
            return [];
        }

        $estudiantes = [];
        $filas = $hojas[0];

        $data['codigo_modular'] = trim($filas[8]['F']);
        $data['modalidad'] = trim($filas[8]['K']);
        $data['nivel'] = trim($filas[8]['M']);
        $data['turno'] = trim($filas[8]['O']);

        // Reemplazar texto a códigos identificadores
        $sexos = [
            'HOMBRE' => 'M',
            'MUJER' => 'F',
            'H' => 'M',
            'F' => 'F',
            'M' => 'M'
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
                if ( trim($fila['L']) == '') {
                    continue;
                }
                // Limpiar datos de la fila
                $fila = array_map('trim', $fila);

                // Formatear fecha de nacimiento a Y-m-d
                $fecha_nacimiento_formateada = NULL;
                if ($fila['Y'] != '') {
                    if( strpos($fila['Y'], '/') !== false ) {
                        $fecha_nacimiento = DateTimeImmutable::createFromFormat('d/m/Y', $fila['Y']);
                        $fecha_nacimiento_formateada = date_format($fecha_nacimiento, 'Y-m-d');
                    } elseif( strpos($fila['Y'], '-') == 4 ) {
                        $fecha_nacimiento = DateTimeImmutable::createFromFormat('Y', $fila['Y']);
                        $fecha_nacimiento_formateada = $fila['Y'];
                    } elseif( strpos($fila['Y'], '-') == 2 ) {
                        $fecha_nacimiento = DateTimeImmutable::createFromFormat('d-m-Y', $fila['Y']);
                        $fecha_nacimiento_formateada = date_format($fecha_nacimiento, 'Y-m-d');
                    } else {
                        $fecha_nacimiento_formateada = NULL;
                    }
                }
                // Formatear datos de estudiantes y padres en nuevo array
                $estudiantes[] = array(
                    'grado' => $fila['C'],
                    'seccion' => $fila['D'],
                    'cod_tipo_documento' => array_search(strtoupper($fila['E']), $tipos_docs) ?: '00',
                    'documento' => $fila['I'],
                    'validado_reniec' => $fila['J'],
                    'codigo_estudiante' => $fila['L'],
                    'paterno' => $fila['N'],
                    'materno' => $fila['R'],
                    'nombres' => $fila['U'],
                    'sexo' => $sexos[strtoupper($fila['X'])],
                    'nacimiento' => $fecha_nacimiento_formateada,
                    'estado_matricula' => $fila['AA'],
                    'tipo_vacante' => $fila['AB'],
                );
            }
        }

        $data['estudiantes'] = $estudiantes;
        
        return $data;
    }

}