<?php

namespace App\Http\Controllers\doc;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use App\Models\doc\ActividadesGestion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;
use Illuminate\Support\Facades\Gate;

class CargaNoLectivasController extends Controller
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function validate(Request $request)
    {
        $request->validate(
            [
                'opcion' => 'required',
            ],
            [
                'opcion.required' => 'Hubo un problema al obtener la acción',
            ]
        );
        $request['valorBusqueda'] = is_null($request->valorBusqueda)
            ? null
            : (is_numeric($request->valorBusqueda)
                ? $request->valorBusqueda
                : ($this->hashids->decode($request->valorBusqueda)[0] ?? null));

        $request['iCargaNoLectivaId'] = is_null($request->iCargaNoLectivaId)
            ? null
            : (is_numeric($request->iCargaNoLectivaId)
                ? $request->iCargaNoLectivaId
                : ($this->hashids->decode($request->iCargaNoLectivaId)[0] ?? null));

        $request['iSemAcadId'] = is_null($request->iSemAcadId)
            ? null
            : (is_numeric($request->iSemAcadId)
                ? $request->iSemAcadId
                : ($this->hashids->decode($request->iSemAcadId)[0] ?? null));

        $request['iYAcadId'] = is_null($request->iYAcadId)
            ? null
            : (is_numeric($request->iYAcadId)
                ? $request->iYAcadId
                : ($this->hashids->decode($request->iYAcadId)[0] ?? null));

        $request['iDocenteId'] = is_null($request->iDocenteId)
            ? null
            : (is_numeric($request->iDocenteId)
                ? $request->iDocenteId
                : ($this->hashids->decode($request->iDocenteId)[0] ?? null));

        $parametros = [
            $request->opcion,
            $request->valorBusqueda      ?? '-',
            $request->iCargaNoLectivaId  ?? NULL,
            $request->iSemAcadId         ?? NULL,
            $request->iYAcadId           ?? NULL,
            $request->iDocenteId         ?? NULL,
            $request->iEstado            ?? NULL,
            $request->iSesionId          ?? NULL,
            $request->dtCreado           ?? NULL,
            $request->dtActualizado      ?? NULL,
            $request->iCredId            ?? NULL,
            $request->iSedeId            ?? NULL,
        ];

        return $parametros;
    }

    public function list(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DOCENTE, Perfil::DIRECTOR_IE]]);
            $data = ActividadesGestion::obtenerActividades($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function store(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DOCENTE, Perfil::DIRECTOR_IE]]);
            $data = ActividadesGestion::guardarActividades($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function update(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DOCENTE]]);
            $data = ActividadesGestion::editarActividades($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function delete(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DOCENTE]]);
            $data = ActividadesGestion::eliminarActividades($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function aprobar(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);
            $data = ActividadesGestion::aprobarActividades($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function observar(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);
            $data = ActividadesGestion::observarActividades($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
