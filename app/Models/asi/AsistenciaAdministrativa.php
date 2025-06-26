<?php

namespace App\Models\asi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsistenciaAdministrativa extends Model
{
    public static function buscarHorarioInstitucion(Request $request){
        $datos = [
            $request->iSedeId,
        ];
        $data = DB::select("EXEC [asi].[SP_SEL_configuracion_horario] ?",$datos);
        return $data;
    }
    public static function guardarHorarioInstitucion(Request $request){
        
        $datos = [
            $request->iSedeId,
            $request->cGrupoNombre,
            $request->cGrupoDescripcion,
            $request->iConfHorarioId,
            $request->tConfHorarioEntTur,
            $request->tConfHorarioSalTur,
            $request->grupoPersonal,
            $request->dtFechaIncio,
            $request->dtFechaFin,
        ];
        $data = DB::select("EXEC [asi].[Sp_INS_grupos] ?,?,?,?,?,?,?,?,?",$datos);
        return $data;
    }
    public static function buscarPersonalInstitucion(Request $request){
        $datos = [
            $request->iSedeId,
            $request->iYAcadId,
        ];
        $data = DB::select("EXEC [asi].[Sp_SEL_GrupoPersonal] ?,?",$datos);
        return $data;
    }
}
