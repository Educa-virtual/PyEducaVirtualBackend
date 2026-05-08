<?php

namespace App\Http\Controllers\acad;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use App\Models\apo\Apoderado;
use App\Models\grl\Persona;
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
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            DB::beginTransaction();

            if ($request->iPersId == null || $request->iPersId == 0) {
                $persona = Persona::insPersonas($request);
                $request->merge([
                    'iPersId' => $persona['iPersId'],
                ]);
            } else {
                Persona::updPersonas($request);
            }

            $data = Apoderado::insApoderado($request);
            DB::commit();
            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (\Exception $e) {
            DB::rollBack();
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarApoderado(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            DB::beginTransaction();

            if ($request->iPersId == null || $request->iPersId == 0) {
                $persona = Persona::insPersonas($request);
                $request->merge([
                    'iPersId' => $persona['iPersId'],
                ]);
            } else {
                Persona::updPersonas($request);
            }

            $data = Apoderado::updApoderado($request);
            DB::commit();
            return FormatearMensajeHelper::ok('Se actualizó la información', $data);
        } catch (\Exception $e) {
            DB::rollBack();
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

    public function borrarApoderado(Request $request)
    {
        try {
            $data = Apoderado::delApoderado($request);
            return FormatearMensajeHelper::ok('Se eliminó la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function buscarPersonaApoderado(Request $request)
    {
        try {
            $data = Apoderado::selPersonaApoderado($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }


}
