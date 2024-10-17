<?php

namespace App\Repositories\aula;

use App\Repositories\Evaluaciones\BancoRepository;
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


        $res = DB::select('exec eval.Sp_SEL_evaluaciones_by_id @_iEvaluacionId = ?', [$iEvaluacionId]);
        return $res;
    }

    public static function obtenerPreguntasEvaluacion($iEvaluacionId)
    {
        return DB::select('exec eval.Sp_SEL_evaluacion_preguntas_by_id @_iEvaluacionId = ?', [$iEvaluacionId]);
    }
}
