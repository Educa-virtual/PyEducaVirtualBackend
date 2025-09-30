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
            $tipo == 'data' ? 0 : 1, //0: Obtener datos, 1: Obtener cantidad
            $request->get('offset', 0),
            $request->get('limit', 20),
            $request->get('opcionSeleccionada'),
            $request->get('criterioBusqueda') ?? '',
            $request->get('institucionSeleccionada'),
            $request->get('perfilSeleccionado'),
            $request->get('iUgelSeleccionada'),
            $request->get('ieSedeSeleccionada')
        ];
        return $parametros;
    }

    public static function obtenerUsuarios(Request $request)
    {
        $parametros = UsuariosService::generarParametrosParaObtenerUsuarios('data', $request);
        $dataUsuarios = Usuario::selUsuarios($parametros);
        $parametros = UsuariosService::generarParametrosParaObtenerUsuarios('cantidad', $request);
        $dataCantidad = Usuario::selUsuarios($parametros);
        $resultado = [
            'totalFilas' => $dataCantidad[0]->totalFilas,
            'dataUsuarios' => $dataUsuarios,
            'fechaServidor' => new Carbon()
        ];
        return $resultado;
    }

    public static function registrarUsuario($request, $iCredId)
    {
        $request->validate([
            'data' => 'required|array',
            'data.cPersNombre' => 'required'
        ]);
        $persona = PersonasService::obtenerPersonaPorDocumento($request->data['cPersDocumento']);
        Usuario::insCredenciales($persona->iPersId, $iCredId);

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

    public static function obtenerPerfilesUsuario($iCredId)
    {
        return Usuario::selPerfilesUsuario($iCredId);
    }

    public static function restablecerClaveUsuario($parametros)
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
}
