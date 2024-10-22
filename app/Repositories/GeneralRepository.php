<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class GeneralRepository
{

    public static function actualizar($schema, $tabla, $jsonParams, $jsonWhere)
    {
        $params = [
            $schema,
            $tabla,
            $jsonParams,
            $jsonWhere
        ];
        return DB::select(
            'exec grl.SP_UPD_EnTablaConJSON 
            @Esquema = ?, 
            @Tabla = ?,
            @DatosJSON = ?,
            @CondicionesJSON = ? 
        ',
            $params
        );
    }

    public static function insertar($schema, $tabla, $jsonParams)
    {
        $params = [
            $schema,
            $tabla,
            $jsonParams,
        ];
        // return $params;
        $resp = DB::select('exec grl.SP_INS_EnTablaDesdeJSON    @Esquema = ?, @Tabla = ?, @DatosJSON = ?
            ', $params);
        return $resp;
    }
}
