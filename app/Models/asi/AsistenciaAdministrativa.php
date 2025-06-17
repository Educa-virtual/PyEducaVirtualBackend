<?php

namespace App\Models\asi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsistenciaAdministrativa extends Model
{
    public static function buscarHorarioInstitucion(Request $request){
        $datos = [
            $request["iSedeId"],
        ];
        $data = DB::select("EXEC [asi].[SP_SEL_configuracion_horario] ?",$datos);
        return $data;
    }
}
