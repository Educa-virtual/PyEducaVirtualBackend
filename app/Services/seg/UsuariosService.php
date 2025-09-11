<?php

namespace App\Services\seg;

use App\Helpers\ProteccionCorreoHelper;
use App\Helpers\VerifyHash;
use App\Http\Requests\seg\CambiarContrasenaRequest;
use App\Http\Requests\seg\SolicitarRegistroUsuarioRequest;
use App\Mail\RecuperarPasswordMail;
use App\Models\grl\Persona;
use App\Models\seg\PasswordReset;
use App\Models\seg\Usuario;
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
        $item = $request->data;
        $iPersId = null;
        $mensaje = '';
        $iTipoPersId = ((int)$item['iTipoIdentId'] == 2) ? 2 : 1;

        $persona = Persona::selPersonaPorDocumento($item['cPersDocumento']);
        if ($persona) {
            //Actualizar persona
            $parametros = [
                $persona->iPersId,
                $item['cPersDocumento'],
                $item['cPersPaterno'],
                isset($item['cPersMaterno']) ? $item['cPersMaterno'] : '',
                $item['cPersNombre'],
                isset($item['cPersSexo']) ? $item['cPersSexo'] : "M",
                isset($item['dPersNacimiento']) ? $item['dPersNacimiento'] : null,
                isset($item['iTipoEstCivId']) ? $item['iTipoEstCivId'] : 1,
                NULL,
                $item['cPersRazonSocialNombre'] ?? '',
                '',
                '',
                $item['cPersDomicilio'],
                isset($iCredId) ? $iCredId : null,
                null,
                isset($item['iNacionId']) ? $item['iNacionId'] : null,
                isset($item['iPaisId']) ? (trim($item['iPaisId']) ?: null) : null,
                isset($item['iDptoId']) ? (trim($item['iDptoId']) ?: null) : null,
                isset($item['iPrvnId']) ? (trim($item['iPrvnId']) ?: null) : null,
                isset($item['iDsttId']) ? (trim($item['iDsttId']) ?: null) : null,
                isset($iTipoPersId) ? $iTipoPersId : null,
                $item['iTipoIdentId']
            ];
            Usuario::updPersonas($parametros);
            $iPersId = $persona->iPersId;
        } else {
            $parametros = [
                isset($iTipoPersId) ? $iTipoPersId : null,
                $item['iTipoIdentId'],
                $item['cPersDocumento'],
                $item['cPersPaterno'],
                isset($item['cPersMaterno']) ? $item['cPersMaterno'] : null,
                $item['cPersNombre'],
                isset($item['cPersSexo']) ? $item['cPersSexo'] : "M",
                isset($item['dPersNacimiento']) ? $item['dPersNacimiento'] : null,
                isset($item['iTipoEstCivId']) ? $item['iTipoEstCivId'] : 1,
                NULL,
                $item['cPersRazonSocialNombre'] ?? '',
                '',
                '',
                $item['cPersDomicilio'],
                isset($iCredId) ? $iCredId : null,
                isset($item['iNacionId']) ? $item['iNacionId'] : null,
                isset($item['iPaisId']) ? (trim($item['iPaisId']) ?: null) : null,
                isset($item['iDptoId']) ? (trim($item['iDptoId']) ?: null) : null,
                isset($item['iPrvnId']) ? (trim($item['iPrvnId']) ?: null) : null,
                isset($item['iDsttId']) ? (trim($item['iDsttId']) ?: null) : null,
            ];
            $data = Usuario::insPersonas($parametros);
            $iPersId = !empty($data) ? $data[0]->iPersId : null;
        }
        Usuario::insCredenciales($iPersId, $iCredId);
        $mensaje = 'Se ha registrado el usuario';

        $persona = Usuario::selUsuarioPorIdPersona($iPersId);
        return [
            'data' => $persona,
            'mensaje' => $mensaje
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

    public static function solicitarRegistroUsuario(SolicitarRegistroUsuarioRequest $request)
    {
        ProteccionCorreoHelper::validarEnvioPorIp($request->ip());
    }
}
