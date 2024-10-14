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
            // actualizar
            $resp = DB::select(
                'exec grl.SP_INS_EnTablaDesdeJSON 
                    @Esquema = ?
                    ,@Tabla = ?
                    ,@DatosJSON = ?
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
