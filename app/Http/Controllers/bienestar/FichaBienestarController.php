<?php

namespace App\Http\Controllers\bienestar;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\bienestar\Ficha;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FichaBienestarController extends Controller
{
    private $administran = [
        Perfil::DIRECTOR_IE,
        Perfil::SUBDIRECTOR_IE,
        Perfil::ASISTENTE_SOCIAL,
    ];

    private $registran = [
        Perfil::ESTUDIANTE,
        Perfil::APODERADO,
        Perfil::DOCENTE,
        Perfil::DIRECTOR_IE,
        Perfil::SUBDIRECTOR_IE,
        Perfil::ASISTENTE_SOCIAL,
    ];

    private $ven_reporte = [
        Perfil::ESPECIALISTA_UGEL,
        Perfil::ESPECIALISTA_DREMO,
        Perfil::ADMINISTRADOR_DREMO,
    ];

    /**
     * Obtiene una lista de fichas según los parámetros proporcionados
     * @param Request $request contiene los parámetros de búsqueda
     * @return JsonResponse respuesta con el estado de la operación y los datos obtenidos
     */
    public function listarFichas(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->administran, [Perfil::APODERADO])]);
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
            Gate::authorize('tiene-perfil', [$this->registran]);
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
            Gate::authorize('tiene-perfil', [$this->registran]);
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
            Gate::authorize('tiene-perfil', [$this->administran]);
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
            Gate::authorize('tiene-perfil', [$this->registran]);
            $data = Ficha::selficha($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function crearReporte(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->administran, $this->ven_reporte)]);
            $data = Ficha::selFichaReporteParametros($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verReporte(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [array_merge($this->administran, $this->ven_reporte)]);
            $data = Ficha::selFichaReporte($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
