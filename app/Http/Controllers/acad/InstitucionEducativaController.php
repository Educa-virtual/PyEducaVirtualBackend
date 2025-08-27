<?php

namespace App\Http\Controllers\acad;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\DB;

class InstitucionEducativaController extends Controller
{
    public function obtenerInstitucionesEducativas()
    {
        try {
            $insituciones = DB::select('EXEC ere.SP_SEL_instituciones');
            return FormatearMensajeHelper::ok('Datos obtenidos correctamente', $insituciones);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function obtenerIePorUgel($iUgelId) {

    }

    public function obtenerSedesIe($iIieeId) {
        try {
            $sedes=DB::select('SELECT iSedeId, iIieeId, cSedeNombre, iEstado FROM acad.sedes WHERE iIieeId=?',[$iIieeId]);
            return FormatearMensajeHelper::ok('Datos obtenidos correctamente', $sedes);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
