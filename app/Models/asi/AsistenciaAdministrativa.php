<?php

namespace App\Models\asi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AsistenciaAdministrativa extends Model
{   
    public static function guardarAsistenciaEstudiante(Request $request){
        $iPersId = $request->iPersId;
        $archivos = $request->file('archivos');
        $ruta = 'justificaciones/'.$iPersId;

        if ($archivos) {
            $documento = Storage::disk('public')->put($ruta,$archivos);
            $asistencia['justificar'] = $ruta.'/'.basename($documento);
        }

        $datos = [
            $request->iEstudianteId,
            $request->dtAsistencia,
            $request->iTipoAsiId,
            $request->iSeccionId,
            $request->iSedeId,
            $request->iYAcadId,
            $request->iNivelGradoId,
            $request->idAsistencia ?? NULL,
            $request->iMatrId ?? NULL,
        ];
        $enviados = str_repeat('?,',count($datos)-1).'?';
        $data = DB::select("EXEC asi.Sp_INS_asistencia_general_estudiante ".$enviados,$datos);
        return $data;
    }
    public static function guardarAsistenciaGeneral(Request $request){
        
        $asistencia = json_decode($request->asistencia,true);
        $iPersId = $request->iPersId;
        $archivos = $request->file('archivos');
        $ruta = 'justificaciones/'.$iPersId;
        if ($archivos) {
            foreach ($archivos as $index => $archivo) {
                $documento = Storage::disk('public')->put($ruta,$archivo);
                $asistencia[$index]['justificar'] = $ruta.'/'.basename($documento);
            }
        }
     
        $datos = [
            json_encode($asistencia),
            $request->dtAsistencia,
            $request->iSedeId,
            $request->iYAcadId,
        ];
        
        $enviados = str_repeat('?,',count($datos)-1).'?';
        $data = DB::select("EXEC asi.Sp_INS_asistencia_general ".$enviados,$datos);
        return $data;
    }
    public static function buscarAlumnos(Request $request){
        $datos = [
            $request->opcion,
            $request->iGradoId ?? NULL,
            $request->iSeccionId ?? NULL,
            $request->iSedeId,          
            $request->iYAcadId,
            $request->cEstCodigo ?? NULL,
            $request->cPersDocumento ?? NULL,
            $request->dtAsistencia,
        ];
        $enviados = str_repeat('?,',count($datos)-1).'?';
        $data = DB::select("EXEC [asi].[SP_SEL_buscarAlumnos] ".$enviados,$datos);
        return $data;
    }
    public static function buscarAsisnteciaGeneral(Request $request){
        $datos = [
            $request->opcion,
            $request->iGradoId ?? NULL,
            $request->iSeccionId ?? NULL,
            $request->iSedeId,
            $request->iYAcadId,
            $request->mes,
            $request->cPersDocumento ?? NULL,
            $request->cEstCodigo ?? NULL,
        ];
        $enviados = str_repeat('?,',count($datos)-1).'?';
        $data = DB::select("EXEC [asi].[Sp_SEL_asistencia_general] ".$enviados,$datos);
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
