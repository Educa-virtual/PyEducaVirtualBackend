<?php

namespace App\Http\Controllers\acad;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use App\Models\apo\Apoderado;
use App\Models\grl\Persona;
use App\Models\seg\Usuario;
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
use PhpParser\Node\UseItem;

class ApoderadoController extends Controller
{
    public function listarApoderados(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE, Perfil::DOCENTE]]);
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

            $credencial = Usuario::insCredencial($request);
            $request->merge([
                'iCredId' => $credencial['iCredId'],
                'iPerfilId' => Perfil::APODERADO,
            ]);
            Usuario::insPerfil($request);

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

            $credencial = Usuario::insCredencial($request);
            $request->merge([
                'iCredId' => $credencial['iCredId'],
                'iPerfilId' => Perfil::APODERADO,
            ]);
            Usuario::insPerfil($request);

            $data = Apoderado::updApoderado($request);
            DB::commit();
            return FormatearMensajeHelper::ok('Se actualizó la información', $data);
        } catch (\Exception $e) {
            DB::rollBack();
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarApoderadoEstado(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Apoderado::updApoderadoEstado($request);
            return FormatearMensajeHelper::ok('Se actualizó la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verApoderado(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE, Perfil::DOCENTE]]);
            $data = Apoderado::selApoderado($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarApoderado(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Apoderado::delApoderado($request);
            return FormatearMensajeHelper::ok('Se eliminó la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function buscarPersonaApoderado(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            $data = Apoderado::selPersonaApoderado($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }


}
