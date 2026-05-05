<?php

namespace App\Http\Controllers\acad;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use App\Models\apo\Apoderado;
use App\Services\acad\MatriculasService;
use App\Services\apo\ApoderadosService;
use App\Services\ParseSqlErrorService;
use Exception;
use Hashids\Hashids;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ApoderadoController extends Controller
{
    public function listarApoderados(Request $request)
    {
        try {
            $data = Apoderado::selApoderados($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function guardarApoderado(Request $request)
    {
        try {
            $data = Apoderado::insApoderado($request);
            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarApoderado(Request $request)
    {
        try {
            $data = Apoderado::updApoderado($request);
            return FormatearMensajeHelper::ok('Se actualizó la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verApoderado(Request $request)
    {
        try {
            $data = Apoderado::selApoderado($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function obtenerEstudiantes()
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::APODERADO]]);
            $data = ApoderadosService::obtenerEstudiantesPorApoderado(Auth::user()->iPersId);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }


}
