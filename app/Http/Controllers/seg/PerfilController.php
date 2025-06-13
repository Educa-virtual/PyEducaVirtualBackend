<?php

namespace App\Http\Controllers\seg;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerfilController extends Controller
{
    public function obtenerPerfiles(Request $request)
    {
        try {
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
        }
    }
}
