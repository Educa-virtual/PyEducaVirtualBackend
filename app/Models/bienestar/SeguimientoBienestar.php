<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SeguimientoBienestar
{
    public static function selSeguimientoParametros($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC obe.Sp_SEL_seguimientoParametros $placeholders", $parametros);
    }

    public static function selSeguimientos($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        try {
            return DB::select("EXEC obe.Sp_SEL_seguimientos $placeholders", $parametros);
        } catch(\Exception $e) {
            // Manejar error en caso de que no se devuelva ningún resultado
            if (str_contains($e->getMessage(), 'contains no fields')) {
                return [];
            }
            throw $e;
        }
    }

    public static function selSeguimientosPersona($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iYAcadId,
            $request->iPersId,
            $request->iTipoSeguimId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        try {
            return DB::select("EXEC obe.Sp_SEL_seguimientosPersona $placeholders", $parametros);
        } catch(\Exception $e) {
            // Manejar error en caso de que no se devuelva ningún resultado
            if (str_contains($e->getMessage(), 'contains no fields')) {
                return [];
            }
            throw $e;
        }
    }

    public static function selSeguimiento($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iSeguimId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC obe.Sp_SEL_seguimiento $placeholders", $parametros);
    }

    public static function insSeguimiento($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iYAcadId,
            $request->iPersId,
            $request->iTipoSeguimId,
            $request->iPrioridad,
            $request->iFase,
            $request->dSeguimFecha,
            $request->cSeguimArchivo,
            $request->cSeguimDescripcion,
            $request->iMatrId,
            $request->iPersIeId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::insert("EXEC obe.Sp_INS_seguimiento $placeholders", $parametros);
    }

    public static function updSeguimiento($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iSeguimId,
            $request->iPersId,
            $request->iTipoSeguimId,
            $request->iPrioridad,
            $request->iFase,
            $request->dSeguimFecha,
            $request->cSeguimArchivo,
            $request->cSeguimDescripcion,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC obe.Sp_UPD_seguimiento $placeholders", $parametros);
    }

    public static function delSeguimiento($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iEncuId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::delete("EXEC obe.Sp_DEL_seguimiento $placeholders", $parametros);
    }

    public static function selDatosPersona($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iYAcadId,
            $request->iSedeId,
            $request->iTipoPers,
            $request->iMatrId,
            $request->iEstudianteId,
            $request->iDocenteId,
            $request->iPersId,
            $request->cEstCodigo,
            $request->iTipoIdentId,
            $request->cPersDocumento,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_SEL_datosPersona $placeholders", $parametros);
    }
}
