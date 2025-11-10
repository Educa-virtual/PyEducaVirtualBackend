<?php

namespace App\Models\aula;

use App\Helpers\VerifyHash;
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
        $procedimiento = "EXEC aula.SP_SEL_obtenerSesionesArea ".$solicitud;

        $data = DB::select($procedimiento, $parametros);
        return $data;
    }
    public static function obtenerSesionesArea(Request $request){

        $iDocenteId = VerifyHash::decodes($request->iDocenteId);

        $parametros = [
            $iDocenteId
            ,$request->iYAcadId
            ,$request->iSedeId
        ];

        $solicitud = str_repeat('?,', count($parametros)-1).'?';
        $procedimiento = "EXEC aula.SP_SEL_obtenerActividadSesionesArea ".$solicitud;

        $data = DB::select($procedimiento, $parametros);
        return $data;
    }
    public static function obtenerProgramacionActividadArea(Request $request){

        $parametros = [
            $request->iContenidoSemId
        ];

        $solicitud = str_repeat('?,', count($parametros)-1).'?';
        $procedimiento = "EXEC aula.SP_SEL_obtenerProgramacionActividadArea ".$solicitud;

        $data = DB::select($procedimiento, $parametros);
        return $data;
    }
}
