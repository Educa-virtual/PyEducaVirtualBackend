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

    /**
     * Inserta un registro de seguimiento
     * @param Request $request contiene los datos a insertar
     * @return mixed devuelve el id del registro insertado
     */
    public static function insSeguimiento($request)
    {
        $parametros = [
            SeguimientoBienestar::formatearDatos($request->iCredEntPerfId, 'int'),
            SeguimientoBienestar::formatearDatos($request->iYAcadId, 'int'),
            SeguimientoBienestar::formatearDatos($request->iPersId, 'int'),
            SeguimientoBienestar::formatearDatos($request->iTipoSeguimId, 'int'),
            SeguimientoBienestar::formatearDatos($request->iPrioridad, 'int'),
            SeguimientoBienestar::formatearDatos($request->iFase, 'int'),
            SeguimientoBienestar::formatearDatos($request->dSeguimFecha, 'date'),
            SeguimientoBienestar::formatearDatos($request->cSeguimArchivo, 'string'),
            SeguimientoBienestar::formatearDatos($request->cSeguimDescripcion, 'string'),
            SeguimientoBienestar::formatearDatos($request->iMatrId, 'int'),
            SeguimientoBienestar::formatearDatos($request->iPersIeId, 'int'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC obe.Sp_INS_seguimiento $placeholders", $parametros);
    }

    /**
     * Actualiza un registro de seguimiento
     * @param Request $request contiene los datos a actualizar
     * @return mixed devuelve el id del registro actualizado
     */
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
        return DB::selectOne("EXEC obe.Sp_UPD_seguimiento $placeholders", $parametros);
    }

    /**
     * Actualiza el archivo de un registro de seguimiento
     * @param Request $request contiene los datos a actualizar
     * @return mixed devuelve true o false dependiendo si se actualizó o no
     */
    public static function updSeguimientoArchivo($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iSeguimId,
            $request->cSeguimArchivo,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::update("EXEC obe.Sp_UPD_seguimientoArchivo $placeholders", $parametros);
    }

    /**
     * Borra un registro de seguimiento
     * @param Request $request contiene el id del registro a borrar
     * @return mixed devuelve datos del registro borrado
     */
    public static function delSeguimiento($request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iSeguimId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC obe.Sp_DEL_seguimiento $placeholders", $parametros);
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

    private static function formatearDatos($valor, $tipo = 'int'|'string'|'bool'|'null'|'date')
    {
        if( in_array($valor, ['null', 'NULL', 'undefined']) ) {
            return null;
        }
        switch ($tipo) {
            case 'int':
                return is_numeric($valor) ? intval($valor) : null;
            case 'string':
                return is_string($valor) ? $valor : null;
            case 'bool':
                if (is_bool($valor)) {
                    return boolval($valor);
                } elseif (is_numeric($valor)) {
                    if (intval($valor) === 1) {
                        return true;
                    } else {
                        return false;
                    }
                }
                return null;
            case 'date':
                $fecha = substr($valor, 0, 10);
                return is_string($fecha) ? date('Y-m-d', strtotime($fecha)) : null;
            case 'null':
                return null;
            default:
                return null;
        }
    }
}
