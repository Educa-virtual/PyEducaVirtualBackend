<?php

namespace App\Http\Controllers\seg;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuloAdministrativoController extends Controller
{
    public function obtenerModulos(Request $request)
    {
        try {
            $modulos = DB::select("SELECT iModuloId, cModuloNombre, iModuloOrden,iModuloEstado,iPerfilId FROM seg.modulos
            WHERE (iModuloId<10 and iModuloEstado=1)"); //(iModuloId=1012 and iModuloEstado=1) or
            return FormatearMensajeHelper::ok('Datos obtenidos correctamente', $modulos);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }

        /*try {
            switch ($request->get('tipo')) {
                case 'ie':
                    $in = '4,7,8';
                    break;
                case 'dremo':
                    $in = '1,2,6';
                    break;
                case 'ugel':
                    $in = '5';
                    break;
            }
            $perfiles = DB::select('SELECT iPerfilId, cPerfilNombre,iPerfilOrden FROM seg.perfiles
WHERE iTipoPerfilId IN (' . $in . ') ORDER BY cPerfilNombre');
            return FormatearMensajeHelper::ok('Datos obtenidos correctamente', $perfiles);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }*/
    }
}
