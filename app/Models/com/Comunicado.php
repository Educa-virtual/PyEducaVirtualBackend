<?php

namespace App\Models\com;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comunicado extends Model
{
    public static function selComunicados($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC enc.Sp_SEL_comunicados $placeholders", $parametros);
    }

    public static function selComunicadoParametros($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_SEL_comunicadoParametros $placeholders", $parametros);
    }

    public static function selComunicado($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iComunicadoId,
            $request->iTipoUsuario
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_SEL_comunicado $placeholders", $parametros);
    }

    public static function insComunicado($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iTipoComId,
            $request->iPrioridadId,
            $request->cComunicadoTitulo,
            $request->cComunicadoDescripcion,
            $request->dtComunicadoEmision,
            $request->dtComunicadoHasta,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_INS_comunicado $placeholders", $parametros);
    }

    public static function updComunicado($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iComunicadoId,
            $request->iTipoComId,
            $request->iPrioridadId,
            $request->cComunicadoTitulo,
            $request->cComunicadoDescripcion,
            $request->dtComunicadoEmision,
            $request->dtComunicadoHasta,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_UPD_comunicado $placeholders", $parametros);
    }

    public static function delComunicado($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iComunicadoId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_DEL_comunicado $placeholders", $parametros);
    }

    public static function selGrupoCantidad($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->jsonGrupo,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_SEL_comunicadoGrupo $placeholders", $parametros);
    }
}
