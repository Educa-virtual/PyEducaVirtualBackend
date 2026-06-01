<?php

namespace App\Models\doc;

use App\Helpers\VerifyHash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActividadesGestion extends Model
{
    public static function obtenerActividades(Request $request){
        $iDocenteId = VerifyHash::decodes($request->iDocenteId);
        $parametros = [
            $request->opcion,
            $request->iYAcadId           ?? NULL,
            $iDocenteId                  ?? NULL,
            $request->iSedeId            ?? NULL,
            $request->iCredEntPerfId     ?? NULL,
        ];
        
        $enviar = str_repeat('?,',count($parametros)-1).'?';
        $procedimiento = 'exec doc.Sp_SEL_cargaNoLectivas '.$enviar;
        $data = DB::select($procedimiento, $parametros);
        return $data;
    }

    public static function guardarActividades(Request $request){
        $iDocenteId = VerifyHash::decodes($request->iDocenteId);
        $parametros = [
            $request->iYAcadId                      ?? NULL,
            $iDocenteId                             ?? NULL,
            $request->iSedeId                       ?? NULL,       
            $request->header('iCredEntPerfId')      ?? NULL,
            $request->cNombre                       ?? NULL,
            $request->iTipoCargaNoLectId            ?? NULL,
            $request->nDetCargaNoLectHoras          ?? NULL,
            $request->cDetCargaNoLectEvidencias     ?? NULL,
            $request->cDescripcion                  ?? NULL,
            $request->dtInicio                      ?? NULL,
        ];

        $solicitud = str_repeat('?,', count($parametros)-1).'?';
        $procedimiento = 'exec doc.Sp_INS_cargaNoLectivas '.$solicitud;

        $data = DB::select($procedimiento, $parametros);
        return $data;
    }

    public static function editarActividades(Request $request){
        $parametros = [
            $request->iDetCargaNoLectId             ?? NULL,
            $request->cNombre                       ?? NULL,
            $request->iTipoCargaNoLectId            ?? NULL,
            $request->nDetCargaNoLectHoras          ?? NULL,
            $request->cDetCargaNoLectEvidencias     ?? NULL,
            $request->cDescripcion                  ?? NULL,
            $request->dtInicio                      ?? NULL,
            $request->header('iCredEntPerfId')      ?? NULL,
        ];

        $solicitud = str_repeat('?,', count($parametros)-1).'?';
        $procedimiento = 'exec doc.Sp_UPD_detalleCargaNoLectivas '.$solicitud;

        $data = DB::select($procedimiento, $parametros);
        return $data;
    }

    public static function eliminarActividades(Request $request){
    
        $data = DB::update("UPDATE doc.detalle_carga_no_lectivas SET iEstado = 0 WHERE iDetCargaNoLectId = ?", [$request->iDetCargaNoLectId]);
        return $data;
    }

    public static function obtenerTiposActividades(){
        
        $data = DB::select("SELECT * FROM doc.tipos_carga_no_lectivas");
        return $data;
    }

    public static function aprobarActividades(Request $request){
    
        $data = DB::update("UPDATE doc.detalle_carga_no_lectivas SET iActivo = ? WHERE iDetCargaNoLectId = ?", [$request->iActivo, $request->iDetCargaNoLectId]);
        return $data;
    }

    public static function observarActividades(Request $request){
    
        $data = DB::update("UPDATE doc.detalle_carga_no_lectivas SET cObservacion = ? WHERE iDetCargaNoLectId = ?", [$request->cObservacion, $request->iDetCargaNoLectId]);
        return $data;
    }

}
