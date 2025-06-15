<?php

namespace App\Http\Controllers\bienestar;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\bienestar\Ficha;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FichaBienestarController extends Controller
{
    /**
     * Obtiene una lista de estudiantes asociados a un apoderado
     * @param Request $request contiene el perfil y el año académico
     * @return JsonResponse respuesta con los datos de los estudiantes
     */
    public function listarEstudiantesApoderado(Request $request)
    {
        try {
            $data = Ficha::selfichasApoderado($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    /**
     * Obtiene una lista de fichas según los parámetros proporcionados
     * @param Request $request contiene los parámetros de búsqueda
     * @return JsonResponse respuesta con el estado de la operación y los datos obtenidos
     */
    public function listarFichas(Request $request)
    {
        try {
            $data = Ficha::selfichas($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    /**
     * Si se acepta la declaración jurada, se crea la ficha en blanco
     * @param Request $request envia año académico e id de persona
     * @return JsonResponse contiene el id de la ficha creada
     */
    public function crearFicha(Request $request)
    {
        try {
            $data = Ficha::insFicha($request);
            return FormatearMensajeHelper::ok('Se guardo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    /**
     * Obtiene los parámetros necesarios para registrar una ficha
     * @param Request $request no se usa, pero se mantiene por compatibilidad
     * @return JsonResponse contiene los parámetros para registrar una ficha
     */
    public function obtenerParametrosFicha(Request $request)
    {
        try {
            $data = DB::select('EXEC obe.Sp_SEL_fichaParametros');
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    /**
     * Elimina una ficha
     * @param Request $request contiene el id de la ficha a eliminar
     * @return JsonResponse respuesta con el estado de la operación
     */
    public function borrarFicha(Request $request)
    {
        try {
            $data = Ficha::delFicha($request);
            return FormatearMensajeHelper::ok('Se elimino la ficha', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    /**
     * Obtiene los datos generales de una ficha específica segun id de ficha o persona
     * @param Request $request contiene el id de la ficha, id de la persona y año académico
     * @return JsonResponse respuesta con los datos generales de la ficha
     */
    public function verFicha(Request $request)
    {
        try {
            $data = Ficha::selficha($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
