<?php

namespace App\Repositories\aula;

use Illuminate\Support\Facades\DB;

class ProgramacionActividadesRepository
{

    public static function guardarActualizar(string $datosJson)
    {

        $params = json_decode($datosJson);

        $esquema = 'aula';
        $tabla = 'programacion_actividades';
        $condiciones = json_encode([
            [
                'COLUMN_NAME' => "iProgActId",
                'VALUE' => $params->iProgActId
            ]
        ]);

        $paramsDB = [
            $esquema,
            $tabla,
            $datosJson
        ];

        if ($params->iProgActId === 0) {
            // insertar
            $resp = DB::select(
                'exec grl.SP_INS_EnTablaDesdeJSON
                    @Esquema = ?
                    ,@Tabla = ?
                    ,@DatosJSON = ?
                    ',
                $paramsDB
            );
            return $resp[0];
        } else {
            array_push($paramsDB, $condiciones);
            // actualizar
            $resp = DB::select(
                'exec grl.SP_UPD_EnTablaConJSON
                    @Esquema = ?
                    ,@Tabla = ?
                    ,@DatosJSON = ?
                    ,@CondicionesJSON = ?
            ',
                $paramsDB
            );
            $resp = $resp[0];
            $resp->id = $params->iProgActId;
            return $resp;
        }
    }

    public static function eliminar($params)
    {
        $parametrosDB = [
            'aula',
            'programacion_actividades',
            'iProgActId',
            $params['iProgActId']
        ];
        $resp = DB::select('exec grl.SP_DEL_RegistroConTransaccion
            @Esquema = ?
            ,@NombreTabla = ?
            ,@CampoID = ?
            ,@ValorID = ?', $parametrosDB);
        return $resp;
    }

    public static function obtenerActividadEvaluacion($params)
    {
        $iEvaluacionId = $params['iEvaluacionId'];

        $res = DB::select('exec eval.SP_SEL_evaluaciones_by_id @_iEvaluacionId = ?', [$iEvaluacionId]);
        return $res;
    }
    public static function obtenerActividadForo($params)
    {

        $iForoId = $params['iForoId'];


        $res = DB::select('exec aula.SP_SEL_Foro @iForoid = ?', [$iForoId]);
        return $res;
    }

    public static function obtenerPreguntasEvaluacion($iEvaluacionId) {}
}

// // codigo para obtener actvidad /tareas
// public static function obtenerActividad($params)
// {
//     $iactividadId = $params['iProgActId'];


//     $res = DB::select('EXEC [aula].[SP_INS_InsertActividades= ?', [iactividadId]);
//     return $res;
// }

// public static function obtenerActividad($iactividadId) {}
// }

// // FIN codigo para obtener actvidad /tareas
