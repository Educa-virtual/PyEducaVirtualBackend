<?php

namespace App\Services;

use Carbon\Carbon;
use App\Services\DividirApellidoNombresService;

class FormatearExcelPadresService
{

    private $dividirApellidoNombresService;

    public function __construct()
    {
        $this->dividirApellidoNombresService = new DividirApellidoNombresService();
    }

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
                        'paterno' => $this->dividirApellidoNombresService->dividir($fila['T'])['paterno'],
                        'materno' => $this->dividirApellidoNombresService->dividir($fila['T'])['materno'],
                        'nombres' => $this->dividirApellidoNombresService->dividir($fila['T'])['nombres'],
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
                        'paterno' => $this->dividirApellidoNombresService->dividir($fila['AK'])['paterno'],
                        'materno' => $this->dividirApellidoNombresService->dividir($fila['AK'])['materno'],
                        'nombres' => $this->dividirApellidoNombresService->dividir($fila['AK'])['nombres'],
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
                        'paterno' => $this->dividirApellidoNombresService->dividir($fila['AS'])['paterno'],
                        'materno' => $this->dividirApellidoNombresService->dividir($fila['AS'])['materno'],
                        'nombres' => $this->dividirApellidoNombresService->dividir($fila['AS'])['nombres'],
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
}