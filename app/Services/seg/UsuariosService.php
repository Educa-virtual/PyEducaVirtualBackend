<?php

namespace App\Services\seg;

use Illuminate\Http\Request;

class UsuariosService
{
    public static function generarParametrosParaObtenerUsuarios($tipo, Request $request)
    {
        switch ($request->get('opcionBusquedaSeleccionada')) {
            case 'documento':
                $documento = $request->get('criterioBusqueda', NULL);
                $apellidos = null;
                $nombres = null;
                break;
            case 'apellidos':
                $documento = null;
                $apellidos = $request->get('criterioBusqueda', NULL);
                $nombres = null;
                break;
            case 'nombres':
                $documento = null;
                $apellidos = null;
                $nombres = $request->get('criterioBusqueda', NULL);
                break;
            default:
                $documento = null;
                $apellidos = null;
                $nombres = null;
                break;
        }
        $parametros = [
            $tipo == 'data' ? 0 : 1, //0: Obtener datos, 1: Obtener cantidad
            $request->get('offset', 0),
            $request->get('limit', 20),
            $documento,
            $apellidos,
            $nombres
        ];
        return $parametros;
    }
}
