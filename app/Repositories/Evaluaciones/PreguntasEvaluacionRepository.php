<?php

namespace App\Repositories\Evaluaciones;

use Illuminate\Support\Facades\DB;

class PreguntasEvaluacionRepository
{

    public static function guardarActualizar() {}

    public static function  guardarActualizarPreguntaEncabezado($params)
    {

        $data = json_encode([
            'iEncabPregId' => $params['iEncabPregId'],
            'cEncabPregTitulo' => $params['cEncabPregTitulo'],
            'cEncabPregContenido' => $params['cEncabPregContenido'],
            'iCursoId' => $params['iCursoId'],
            'iNivelCicloId' => $params['iNivelCicloId'],
            'iDocenteId' => $params['iDocenteId'],
        ]);

        $paramsDB = [
            'eval',
            'encabezado_preguntas',
            $data
        ];

        $condiciones = [
            [
                'COLUMN_NAME' => "iEncabPregId",
                'VALUE' => $params['iEncabPregId']
            ]
        ];
        $condicionesJson = json_encode($condiciones);

        if ($params['iEncabPregId'] == 0) {
            return DB::select('exec grl.SP_INS_EnTablaDesdeJSON
                @Esquema = ?
                ,@Tabla = ?
                ,@DatosJSON = ?
            ', $paramsDB);
        } else {
            array_push($paramsDB, $condicionesJson);
            return DB::select('exec grl.SP_UPD_EnTablaConJSON
                @Esquema = ?
                ,@Tabla = ?
                ,@DatosJSON = ?
                ,@CondicionesJSON = ?
            ', $paramsDB);
        }
    }
}
