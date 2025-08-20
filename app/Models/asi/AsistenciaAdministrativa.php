<?php

namespace App\Models\asi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsistenciaAdministrativa extends Model
{   
    public static function buscarAlumnos(Request $request){
        $datos = [
            $request->iGradoId,
            $request->iSeccionId,
            $request->iSedeId,          
            $request->iYAcadId,
        ];
        $data = DB::select("EXEC [asi].[SP_SEL_buscarAlumnos] ?,?,?,?",$datos);
        return $data;
    }
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
            // $request->grupoPersonal,
            // $request->dtFechaIncio,
            // $request->dtFechaFin,
        ];
        $enviados = str_repeat('?,',count($datos)-1).'?';
        $data = DB::select("EXEC [asi].[Sp_INS_grupos] ".$enviados, $datos);
        return $data;
    }
    public static function actualizarHorarioInstitucion(Request $request){
        
        $datos = [
            $request->iSedeId,
            $request->iGrupoId,
            $request->cGrupoNombre,
            $request->cGrupoDescripcion,
            $request->iConfHorarioId,
            $request->tConfHorarioEntTur,
            $request->tConfHorarioSalTur,
        ];

        $enviados = str_repeat('?,',count($datos)-1).'?';
        $data = DB::select("EXEC [asi].[Sp_UPD_grupos] ".$enviados, $datos);
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
    public static function guardarPersonalInstitucion(Request $request){
        $datos = [
            $request->iGrupoId,
            $request->grupoPersonal,
        ];
        $data = DB::select("EXEC [asi].[Sp_INS_personaGrupo] ?,?",$datos);
        return $data;
    }
    
    public static function editarGrupoInstitucion(Request $request){
        $datos = [
            $request->iSedeId,
            $request->iGrupoId,
            $request->cGrupoNombre,
            $request->cGrupoDescripcion,
            $request->iConfHorarioId,
            $request->tConfHorarioEntTur,
            $request->tConfHorarioSalTur,
            $request->grupoPersonal,
            $request->grupoEliminado,
            $request->dtFechaIncio,
            $request->dtFechaFin,
        ];
        $enviados = str_repeat('?,',count($datos)-1).'?';
        $data = DB::select("EXEC [asi].[Sp_UPD_grupos] ".$enviados,$datos);
        return $data;
    }
}
