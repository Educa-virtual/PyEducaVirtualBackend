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
        $condiciones = [
            [
                'COLUMN_NAME' => "iProgActId",
                'VALUE' => $params->iProgActId
            ]
        ];

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

        $actividades = DB::select('exec ');
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
        $iProgActId = $params['iProgActId'];
        $iActTipoId = $params['iActTipoId'];
        $ixActividadId = $params['ixActividadId'];


        $resp = DB::select('exec ');
    }

    public static function obtenerPreguntasEvaluacion($iEvalPregId)
    {
        DB::select('exec ');
    }
}
