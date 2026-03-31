<?php

namespace App\Http\Controllers\acad;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstitucionEducativaController extends Controller
{
    public function obtenerInstitucionesEducativas(Request $request)
    {
        try {
            $params = [
                $request->header('iCredEntPerfId'),
                $request->iUgelId,
            ];
            $placeholders = implode(',', array_fill(0, count($params), '?'));
            $insituciones = DB::select("EXEC ere.SP_SEL_instituciones $placeholders", $params);
            return FormatearMensajeHelper::ok('Datos obtenidos correctamente', $insituciones);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function obtenerIePorUgel($iUgelId) {}

    public function obtenerSedesIe($iIieeId)
    {
        try {
            $sedes = DB::select('SELECT * FROM acad.sedes WHERE iIieeId=?', [$iIieeId]);
            return FormatearMensajeHelper::ok('Datos obtenidos correctamente', $sedes);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
