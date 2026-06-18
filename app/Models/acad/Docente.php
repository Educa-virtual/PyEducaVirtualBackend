<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class Docente
{
    public static function selDocentePorId($iDocenteId) {
        return DB::selectOne("
            SELECT doc.iDocenteId, per.cPersPaterno, per.cPersMaterno, per.cPersNombre
            FROM acad.docentes AS doc
                INNER JOIN grl.personas AS per ON per.iPersId=doc.iPersId
            WHERE doc.iDocenteId=?", [$iDocenteId]);
    }

    public static function selDocente($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iConfigId,
            $request->iDocenteId,
            $request->iPersId,
            $request->iTipoIdentId ?? 1,
            $request->cPersDocumento,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_SEL_docente $placeholders", $parametros);
    }

    public static function selDocentes($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iConfigId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.Sp_SEL_docentes $placeholders", $parametros);
    }

    public static function insDocente($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPersId,
            $request->iDocenteId,
            $request->iConfigId,
            $request->iYAcadId,
            $request->iSedeId,
            $request->iHorasLabora,
            $request->cMotivo,
            $request->dtPersIeInicio,
            $request->dtPersIeFin,
            $request->cCodigoPlaza,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC acad.Sp_INS_docente $placeholders", $parametros);
    }

    public static function updDocente($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPersIeId,
            $request->iPersId,
            $request->iDocenteId,
            $request->iHorasLabora,
            $request->cMotivo,
            $request->dtPersIeInicio,
            $request->dtPersIeFin,
            $request->cCodigoPlaza,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC acad.Sp_UPD_docente $placeholders", $parametros);
    }

    public static function updDocenteEstado($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPersIeId,
            $request->bActivo,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC acad.Sp_UPD_docenteEstado $placeholders", $parametros);
    }

    public static function delDocente($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iPersIeId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC acad.Sp_DEL_docente $placeholders", $parametros);
    }
}
