<?php

namespace App\Repositories\evaluaciones;

use Illuminate\Support\Facades\DB;

class PreguntasEvaluacionRepository
{

    public static function guardarActualizar() {}

    public static function  guardarActualizarPreguntaEncabezado($params)
    {

        $data = json_encode([
            'idEncabPregId' => $params['idEncabPregId'],
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
                'COLUMN_NAME' => "idEncabPregId",
                'VALUE' => $params['idEncabPregId']
            ]
        ];
        $condicionesJson = json_encode($condiciones);

        if ($params['idEncabPregId'] == 0) {
            $resp = DB::select('exec grl.SP_INS_EnTablaDesdeJSON
                @Esquema = ?
                ,@Tabla = ?
                ,@DatosJSON = ?
            ', $paramsDB);
            return $resp[0];
        } else {
            array_push($paramsDB, $condicionesJson);
            DB::select('exec grl.SP_UPD_EnTablaConJSON
                @Esquema = ?
                ,@Tabla = ?
                ,@DatosJSON = ?
                ,@CondicionesJSON = ?
            ', $paramsDB);
            return ['id' => $params['idEncabPregId']];
        }
    }
}
