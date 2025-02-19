<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FormatearExcelPadresService
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

        $data['codigo_modular'] = trim($filas[8]['M']);
        $data['modalidad'] = trim($filas[8]['S']);
        $data['nivel'] = trim($filas[8]['U']);
        $data['turno'] = trim($filas[8]['V']);

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
                    'documento' => $fila['F'],
                    'validado_reniec' => $fila['G'],
                    'codigo_estudiante' => $fila['H'],
                    'paterno' => $fila['I'],
                    'materno' => $fila['J'],
                    'nombres' => $fila['K'],
                    'sexo' => $sexos[$fila['L']],
                    'nacimiento' => Carbon::createFromFormat('d/m/Y', $fila['N'])->format('Y-m-d'),
                    'estado_matricula' => $fila['R'],
                    'padre' => array(
                        'apenom' => $fila['T'],
                        'paterno' => $this->split_apenom($fila['T'])['paterno'],
                        'materno' => $this->split_apenom($fila['T'])['materno'],
                        'nombres' => $this->split_apenom($fila['T'])['nombres'],
                        'sexo' => $sexos[$fila['Y']],
                        'parentesco' => $fila['AB'],
                        'cod_tipo_documento' => $tipos_docs[$fila['AF']],
                        'documento' => $fila['AG'],
                        'validado_reniec' => $fila['AH'],
                        'correo' => $fila['AI'],
                        'celular' => $fila['AJ'],
                    ),
                    'madre' => array(
                        'apenom' => $fila['AK'],
                        'paterno' => $this->split_apenom($fila['AK'])['paterno'],
                        'materno' => $this->split_apenom($fila['AK'])['materno'],
                        'nombres' => $this->split_apenom($fila['AK'])['nombres'],
                        'sexo' => $sexos[$fila['AL']],
                        'parentesco' => $fila['AM'],
                        'cod_tipo_documento' => $tipos_docs[$fila['AN']],
                        'documento' => $fila['AO'],
                        'validado_reniec' => $fila['AP'],
                        'correo' => $fila['AQ'],
                        'celular' => $fila['AR'],
                    ),
                    'apoderado' => array(
                        'apenom' => $fila['AS'],
                        'paterno' => $this->split_apenom($fila['AS'])['paterno'],
                        'materno' => $this->split_apenom($fila['AS'])['materno'],
                        'nombres' => $this->split_apenom($fila['AS'])['nombres'],
                        'sexo' => $sexos[$fila['AT']],
                        'parentesco' => $fila['AU'],
                        'cod_tipo_documento' => $tipos_docs[$fila['AV']],
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

    public function split_apenom($apenom)
    {
        // Poner todo en mayúsculas
        $apenom = strtoupper($apenom);

        // Reemplazar apellido en blanco con asterisco
        $apenom = str_replace("  ", " * ", $apenom);
        $apenom = str_replace(" - ", " * ", $apenom);

        $partes = explode(" ", $apenom);

        $parte_usable = [];
        foreach ( $partes as $index => $parte ) {
            if (!in_array($parte, ['DE', 'LA', 'DEL', 'LAS', 'LOS', 'DI', 'MC'])) {
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