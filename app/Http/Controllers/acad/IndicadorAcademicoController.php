<?php

namespace App\Http\Controllers\acad;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\acad\IndicadorAcademico;
use App\Enums\Perfil;

class IndicadorAcademicoController extends Controller
{
    public static function verIndicadoresParametros(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[
                Perfil::ADMINISTRADOR_DREMO, Perfil::ESPECIALISTA_DREMO, Perfil::ESPECIALISTA_UGEL, Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE
            ]]);
            $data = IndicadorAcademico::selIndicadoresParametros($request);
            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public static function verIndicadoresMatriculados(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[
                Perfil::ADMINISTRADOR_DREMO, Perfil::ESPECIALISTA_DREMO, Perfil::ESPECIALISTA_UGEL, Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE
            ]]);
            $data = IndicadorAcademico::selIndicadoresMatriculas($request);
            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public static function verIndicadoresDeserciones(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[
                Perfil::ADMINISTRADOR_DREMO, Perfil::ESPECIALISTA_DREMO, Perfil::ESPECIALISTA_UGEL, Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE
            ]]);
            $data = IndicadorAcademico::selIndicadoresDeserciones($request);
            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public static function verIndicadoresFaltasTardanzas(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[
                Perfil::ADMINISTRADOR_DREMO, Perfil::ESPECIALISTA_DREMO, Perfil::ESPECIALISTA_UGEL, Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE
            ]]);
            $data = IndicadorAcademico::selIndicadoresFaltasTardanzas($request);
            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public static function verIndicadoresDesempenos(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[
                Perfil::ADMINISTRADOR_DREMO, Perfil::ESPECIALISTA_DREMO, Perfil::ESPECIALISTA_UGEL, Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE
            ]]);
            $data = IndicadorAcademico::selIndicadoresDesempenos($request);
            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public static function verIndicadoresBajoRendimiento(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[
                Perfil::ADMINISTRADOR_DREMO, Perfil::ESPECIALISTA_DREMO, Perfil::ESPECIALISTA_UGEL, Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE
            ]]);
            $data = IndicadorAcademico::selIndicadoresBajoRendimiento($request);
            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}