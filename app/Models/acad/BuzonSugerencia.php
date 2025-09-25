<?php

namespace App\Models\acad;

use App\Http\Requests\acad\EliminarSugerenciaRequest;
use App\Http\Requests\acad\RegistrarSugerenciaRequest;
use App\Http\Requests\GeneralFormRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BuzonSugerencia extends Model
{
    public static function insBuzonSugerencias(RegistrarSugerenciaRequest $request)
    {
        $request->merge(['iCredEntPerfId' => $request->header('iCredEntPerfId')]);
        $data = DB::selectOne("EXEC [acad].[SP_INS_buzonSugerencias]  ?,?,?,?", [
            $request->iCredEntPerfId,
            $request->cAsunto,
            $request->cSugerencia,
            $request->iPrioridadId
        ]);
        return $data->iSugerenciaId;
    }

    public static function selBuzonSugerenciasEstudiante(Request $request)
    {
        $data = DB::select("EXEC [acad].[SP_SEL_buzonSugerenciasEstudiante] @iCredEntPerfId=?", [
            $request->header('iCredEntPerfId')
        ]);
        return $data;
    }

    public static function insBuzonSugerenciaRespuestaDirector(Request $request) {
        DB::statement("EXEC [acad].[SP_INS_buzonSugerenciaRespuestaDirector] @iCredEntPerfId=?, @iSugerenciaId=?, @cRespuesta=?", [
            $request->header('iCredEntPerfId'),
            $request->iSugerenciaId,
            $request->cRespuesta
        ]);
    }

    public static function selBuzonSugerenciasDirector(Request $request)
    {
        $data = DB::select("EXEC [acad].[SP_SEL_buzonSugerenciasDirector] @iCredEntPerfId=?", [
            $request->header('iCredEntPerfId')
        ]);
        return $data;
    }

    public static function delBuzonSugerencias($iSugerenciaId, Request $request)
    {
        $data = DB::statement("EXEC [acad].[SP_DEL_buzonSugerenciasEstudiante] @iCredEntPerfId=?, @iSugerenciaId=?", [
            $request->header('iCredEntPerfId'),
            $iSugerenciaId,
        ]);
        return $data;
    }

    /*public static function responderSugerencia($iSugerenciaId, Request $request)
    {
        $data = DB::statement("EXEC [acad].[SP_UPD_responderSugerencia] @iCredEntPerfId=?, @iSugerenciaId=?, @cRespuesta=?", [
            $request->header('iCredEntPerfId'),
            $iSugerenciaId,
            $request->cRespuesta
        ]);
        return $data;
    }*/

    /*public static function obtenerDetalleSugerencia($iSugerenciaId, Request $request)
    {
        $data = DB::selectOne("EXEC [acad].[SP_SEL_detalleSugerencia] @iCredEntPerfId=?, @iSugerenciaId=?", [
            $request->header('iCredEntPerfId'),
            $iSugerenciaId
        ]);
        return $data;
    }*/

    /*public static function cambiarEstadoSugerencia($iSugerenciaId, $cEstado, Request $request)
    {
        $data = DB::statement("EXEC [acad].[SP_UPD_estadoSugerencia] @iCredEntPerfId=?, @iSugerenciaId=?, @cEstado=?", [
            $request->header('iCredEntPerfId'),
            $iSugerenciaId,
            $cEstado
        ]);
        return $data;
    }*/
}
