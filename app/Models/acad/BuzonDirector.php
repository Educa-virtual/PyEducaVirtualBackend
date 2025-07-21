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
    public static function responderSugerencia($iSugerenciaId, Request $request)
    {
        $data = DB::statement("EXEC [acad].[SP_UPD_responderSugerencia] @iCredEntPerfId=?, @iSugerenciaId=?, @cRespuesta=?", [
            $request->header('iCredEntPerfId'), 
            $iSugerenciaId,
            $request->cRespuesta
        ]);
        return $data;
    }
    public static function obtenerDetalleSugerencia($iSugerenciaId, Request $request)
    {
        $data = DB::selectOne("EXEC [acad].[SP_SEL_detalleSugerencia] @iCredEntPerfId=?, @iSugerenciaId=?", [
            $request->header('iCredEntPerfId'),
            $iSugerenciaId
        ]);
        return $data;
    }
    public static function cambiarEstadoSugerencia($iSugerenciaId, $cEstado, Request $request)
    {
        $data = DB::statement("EXEC [acad].[SP_UPD_estadoSugerencia] @iCredEntPerfId=?, @iSugerenciaId=?, @cEstado=?", [
            $request->header('iCredEntPerfId'),
            $iSugerenciaId,
            $cEstado
        ]);
        return $data;
    }

}