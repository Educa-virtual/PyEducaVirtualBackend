<?php

namespace App\Services\seg;

use App\Helpers\ProteccionCorreoHelper;
use App\Helpers\VerifyHash;
use App\Http\Requests\seg\CambiarContrasenaRequest;
use App\Http\Requests\seg\SolicitarRegistroUsuarioRequest;
use App\Mail\RecuperarPasswordMail;
use App\Mail\seg\SolicitudRegistroUsuarioMail;
use App\Models\grl\Persona;
use App\Models\seg\PasswordReset;
use App\Models\seg\SolicitudRegistroUsuario;
use App\Models\seg\Usuario;
use App\Services\grl\PersonasService;
use Carbon\Carbon;
use Exception;
use Faker\Calculator\Ean;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UsuariosService
{
    public static function generarParametrosParaObtenerUsuarios($tipo, Request $request)
    {
        $parametros = [
            'iCredEntPerfId' => $request->header('iCredEntPerfId'),
            'soloTotal' => $tipo == 'data' ? 0 : 1, //0: Obtener datos, 1: Obtener cantidad
            'offset' => $request->get('offset', 0),
            'limit' => $request->get('limit', 20),
            'opcionBusqueda' => $request->get('opcionSeleccionada'),
            'criterioBusqueda' => $request->get('criterioBusqueda') ?? '',
            'institucionSeleccionada' => $request->get('institucionSeleccionada'),
            'perfilSeleccionado' => $request->get('perfilSeleccionado'),
            'iUgelSeleccionada' => $request->get('iUgelSeleccionada'),
            'ieSedeSeleccionada' => $request->get('ieSedeSeleccionada'),
            'iPersId' => $request->get('iPersId', null),
            'nivelSeleccionado' => $request->get('nivelSeleccionado', null),
            'estadoSeleccionado' => $request->get('estadoSeleccionado', null),
            'fechaDesde' => $request->get('fechaDesde', null),
            'fechaHasta' => $request->get('fechaHasta', null),
            'columnaOrdenar' => $request->get('columnaOrdenar', null),
            'direccionOrdenar' => $request->get('direccionOrdenar', null),
        ];
        return $parametros;
    }

    public static function obtenerUsuarios(Request $request)
    {
        $parametros = UsuariosService::generarParametrosParaObtenerUsuarios('data', $request);
        $dataUsuarios = Usuario::selUsuarios((object) $parametros);
        $parametros = UsuariosService::generarParametrosParaObtenerUsuarios('cantidad', $request);
        $dataCantidad = Usuario::selUsuarios((object) $parametros);
        $resultado = [
            'totalFilas' => $dataCantidad[0]->totalFilas,
            'dataUsuarios' => $dataUsuarios,
            'fechaServidor' => new Carbon()
        ];
        return $resultado;
    }

    public static function registrarUsuario($request)
    {
        $request->validate([
            'cPersDocumento' => 'required|string|size:8',
        ]);
        $persona = PersonasService::obtenerPersonaPorDocumento($request->cPersDocumento);
        $parametros = [
            'iEntId' => 10,
            'iPersId' => $persona->iPersId,
            'iCredEntPerfId' => $request->header('iCredEntPerfId'),
        ];
        Usuario::insCredenciales((object) $parametros);

        $persona = Usuario::selUsuarioPorIdPersona($persona->iPersId);
        return [
            'data' => $persona,
            'mensaje' => 'Se ha registrado el usuario'
        ];
    }

    public static function cambiarEstadoUsuario($parametros)
    {
        Usuario::updiCredEstadoCredencialesXiCredId($parametros);
        $mensaje = $parametros[1] == 1 ? 'activado' : 'desactivado';
        return 'El usuario ha sido ' . $mensaje;
    }

    public static function actualizarFechaVigenciaUsuario($iCredId, Request $request)
    {
        Usuario::updFechaVigenciaCuenta($iCredId, $request->dtCredCaduca);
    }

    public static function asignarPerfilUsuario($iCredId, Request $request)
    {
        switch ($request->opcion) {
            case 'dremo':
                Usuario::insPerfilDremo($iCredId, $request);
                break;
            case 'ugel':
                Usuario::insPerfilUgel($iCredId, $request);
                break;
            case 'iiee':
                Usuario::insPerfilIiee($iCredId, $request);
                break;
            default:
                throw new Exception('Opción no válida');
        }
    }

    public static function eliminarPerfilUsuario($iCredId, $parametros)
    {
        Usuario::delCredencialesEntidadesPerfiles($iCredId, $parametros);
    }

    public static function restablecerClaveUsuario(Object $parametros)
    {
        Usuario::updReseteoClaveCredencialesXiCredId($parametros);
    }

    public static function actualizarContrasenaUsuario($usuario, CambiarContrasenaRequest $request)
    {
        $parametros = [
            $usuario->iCredId,
            $usuario->iPersId,
            $request->contrasenaActual,
            $request->contrasenaNueva
        ];
        Usuario::updCredenciasUpdatePassword($parametros);
    }

    public static function obtenerDetallesCredencialEntidad($iCredEntPerfId) {
        return Usuario::selDetallesCredencialEntidad($iCredEntPerfId);
    }
}
