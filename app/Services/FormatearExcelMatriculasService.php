<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FormatearExcelMatriculasService
{
    public function __invoke($hojas)
    {
        return $this->formatear($hojas);
    }

    public function formatear($hojas)
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
        $sexos = ['Hombre' => 'M', 'Mujer' => 'F'];
        $tipos_docs = ['DNI' => '01', 'CE' => '04', 'RUC' => '06', 'PAS' => '07', 'OT' => '00'];

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
                    'cod_tipo_documento' => $tipos_docs[$fila['E']],
                    'documento' => $fila['I'],
                    'validado_reniec' => $fila['J'],
                    'codigo_estudiante' => $fila['L'],
                    'paterno' => $fila['N'],
                    'materno' => $fila['R'],
                    'nombres' => $fila['U'],
                    'sexo' => $sexos[$fila['X']],
                    'nacimiento' => Carbon::createFromFormat('d/m/Y', $fila['Y'])->format('Y-m-d'),
                    'estado_matricula' => $fila['AA'],
                    'tipo_vacante' => $fila['AB'],
                );
            }
        }

        $data['estudiantes'] = $estudiantes;
        
        return $data;
    }

}