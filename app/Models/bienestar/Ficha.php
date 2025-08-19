<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;

class Ficha
{
    public static function selfichaParametros()
    {
        return DB::select('EXEC obe.Sp_SEL_fichaParametros');
    }

    public static function selfichas($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->iFichaDGId,
            $request->iPersId,
            $request->iNivelTipoId,
            $request->iNivelGradoId,
            $request->iSeccionId,
            $request->iDsttId,
            $request->cPersSexo,
            $request->iUgelId,
            $request->iIieeId,
            $request->iSedeId,
            $request->iTipoSectorId,
            $request->iZonaId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_fichas ' . $placeholders, $parametros);
    }

    public static function selfichasApoderado($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_fichasApoderado ' . $placeholders, $parametros);
    }

    public static function selficha($request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->iPersId,
            $request->iYAcadId,
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_SEL_ficha ' . $placeholders, $parametros);
    }

    public static function insFicha($request)
    {
        $parametros = [
            $request->iPersId,
            $request->iYAcadId,
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_INS_ficha ' . $placeholders, $parametros);
    }

    public static function delFicha($request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select('EXEC obe.Sp_DEL_ficha ' . $placeholders, $parametros);
    }

    public static function selFichaReporteParametros($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne('EXEC obe.Sp_SEL_fichaReporteParametros ' . $placeholders, $parametros);
    }

    public static function selFichaReporte($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->iNivelTipoId,
            $request->iNivelGradoId,
            $request->iSeccionId,
            $request->iDsttId,
            $request->cPersSexo,
            $request->iUgelId,
            $request->iIieeId,
            $request->iSedeId,
            $request->iTipoSectorId,
            $request->iZonaId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne('EXEC obe.Sp_SEL_fichaReporte ' . $placeholders, $parametros);
    }

    public static function selFichaImpresion($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iFichaDGId,
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC obe.Sp_SEL_fichaImpresion $placeholders", $parametros);
    }
}
