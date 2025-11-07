<?php

namespace App\Models\aula;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AulaVirtual extends Model
{
    public static function obtenerSesiones(Request $request){

        $parametros = [
            $request->idDocCursoId,
        ];

        $solicitud = str_repeat('?,', count($parametros)-1).'?';
        $procedimiento = "EXEC aula.obtenerSesionesArea ".$solicitud;

        $data = DB::select($procedimiento, $parametros);
        return $data;
    }
}
