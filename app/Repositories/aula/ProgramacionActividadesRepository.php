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
}
