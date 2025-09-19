<?php

namespace App\Services\seg;

use App\Helpers\ProteccionCorreoHelper;
use App\Http\Requests\seg\SolicitarRegistroUsuarioRequest;
use App\Mail\seg\SolicitudRegistroUsuarioMail;
use App\Models\seg\SolicitudRegistroUsuario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SolicitudesRegistroUsuarioService
{
    public static function registrarSolicitud(SolicitarRegistroUsuarioRequest $request)
    {
        ProteccionCorreoHelper::validarEnvioPorIp($request->ip());
        $solicitudAnterior = SolicitudRegistroUsuario::selExisteSolicitudRegistro($request->cDocumento);
        if ($solicitudAnterior) {
            throw new Exception('Ya existe una solicitud de registro para el documento ingresado en los últimos 10 días.');
        }
        SolicitudRegistroUsuario::insSolicitudRegistroUsuario($request);
        Mail::mailer('mailer_noreply')->to($request->cCorreo)->cc(env('MAIL_SOPORTE_PROYECTO'))->send(new SolicitudRegistroUsuarioMail($request->cNombres));
    }

    private static function generarParametrosParaObtenerRegistros($tipo, Request $request)
    {
        $parametros = [
            $tipo == 'data' ? 0 : 1, //0: Obtener datos, 1: Obtener cantidad
            $request->get('offset', 0),
            $request->get('limit', 20),
            $request->get('fechaCreacionDesde'),
            $request->get('fechaCreacionHasta'),
            $request->get('atendidas')
        ];
        return $parametros;
    }

    public static function obtenerListaSolicitudesRegistro(Request $request)
    {
        //fechaSolicitud
        $parametros = self::generarParametrosParaObtenerRegistros('data', $request);
        $dataRegistros = SolicitudRegistroUsuario::selListaSolicitudesRegistro($parametros);
        $parametros = UsuariosService::generarParametrosParaObtenerUsuarios('cantidad', $request);
        $dataCantidad = SolicitudRegistroUsuario::selListaSolicitudesRegistro($parametros);
        $resultado = [
            'totalFilas' => $dataCantidad[0]->totalFilas,
            'dataRegistros' => $dataRegistros
        ];
        return $resultado;
    }
}
