<?php

namespace App\Services\seg;

use App\Helpers\VerifyHash;
use App\Http\Requests\seg\CambiarContrasenaRequest;
use App\Models\seg\Usuario;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
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
        // Validar los datos de entrada
        $request->validate([
            'data' => 'required|array',
            'data.cPersSexo' => 'required',
            'data.cPersNombre' => 'required'
        ]);
        $item = $request->data;
        $iPersId = null;
        $mensaje = '';

        if (empty($item["iPersId"])) {
            $iTipoPersId = ((int)$item['iTipoIdentId'] == 2) ? 2 : 1;
            $parametros = [
                isset($iTipoPersId) ? $iTipoPersId : null,
                $item['iTipoIdentId'],
                $item['cPersDocumento'],
                $item['cPersPaterno'],
                isset($item['cPersMaterno']) ? $item['cPersMaterno'] : null,
                $item['cPersNombre'],
                $item['cPersSexo'],
                isset($item['cPersCorreo']) ? $item['cPersCorreo'] : null,
                isset($item['cPersCelular']) ? $item['cPersCelular'] : null,
                isset($item['cPersFotografia']) ? (trim($item['cPersFotografia']) ?: null) : null,
                isset($item['cPersTelefono']) ? $item['cPersTelefono'] : null,
                isset($item['cPersDireccion']) ? $item['cPersDireccion'] : null,
                isset($item['cPersReferencia']) ? $item['cPersReferencia'] : null,
                isset($item['cPersDomicilio']) ? (trim($item['cPersDomicilio']) ?: null) : null,
                isset($iCredId) ? $iCredId : null,
                isset($item['iNacionId']) ? $item['iNacionId'] : null,
                isset($item['iPaisId']) ? (trim($item['iPaisId']) ?: null) : null,
                isset($item['iDptoId']) ? (trim($item['iDptoId']) ?: null) : null,
                isset($item['iPrvnId']) ? (trim($item['iPrvnId']) ?: null) : null,
                isset($item['iDsttId']) ? (trim($item['iDsttId']) ?: null) : null,
            ];

            $data = Usuario::insPersonas($parametros);
            $iPersId = !empty($data) ? $data[0]->iPersId : null;

            if ($iPersId) {
                Usuario::insCredenciales($iPersId, $iCredId);
                $mensaje = 'Se ha registrado el usuario';
            } else {
                throw new Exception('Error al registrar el personal');
            }
        } else {
            $iPersId = $item["iPersId"];
            $mensaje = 'El usuario ya se encuentra registrado';
        }
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

    public static function actualizarContrasenaUsuario($usuario, CambiarContrasenaRequest $request) {
        $parametros = [
            $usuario->iCredId,
            $usuario->iPersId,
            $request->contrasenaActual,
            $request->contrasenaNueva
        ];
        Usuario::updCredenciasUpdatePassword($parametros);
    }
}
