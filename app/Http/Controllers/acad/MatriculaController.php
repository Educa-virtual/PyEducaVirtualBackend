<?php

namespace App\Http\Controllers\acad;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use App\Models\acad\CompetenciaCurso;
use App\Models\acad\Estudiante;
use App\Models\acad\Matricula;
use App\Models\acad\YearAcademico;
use App\Services\acad\MatriculasService;
use App\Services\seg\UsuariosService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class MatriculaController extends Controller
{
    public function crearMatricula(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE, Perfil::APODERADO, Perfil::ESTUDIANTE, Perfil::DOCENTE]]);
            $data = Matricula::selMatriculaParametros($request);

            return FormatearMensajeHelper::ok('Se obtuvó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function searchGradoSeccionTurnoConf(Request $request)
    {
        try {
            // LIBRE PARA TODOS LOS PERFILES
            $data = Matricula::selGradoSeccionTurnoConf($request);

            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function listarMatriculas(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE, Perfil::DOCENTE]]);
            $data = Matricula::selMatriculas($request);

            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verMatricula(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE, Perfil::APODERADO, Perfil::ESTUDIANTE]]);
            $data = Matricula::selMatricula($request);

            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarMatricula(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            DB::beginTransaction();
            $data = MatriculasService::registrarMatricula($request);
            DB::commit();

            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (Exception $e) {
            DB::rollback();

            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarMatricula(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            DB::beginTransaction();
            $data = MatriculasService::actualizarMatricula($request);
            DB::commit();

            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (Exception $e) {
            DB::rollback();

            return FormatearMensajeHelper::error($e);
        }
    }

    public function borrarMatricula(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE, Perfil::SUBDIRECTOR_IE]]);
            DB::beginTransaction();
            $data = Matricula::delMatriculaPorId($request);
            // TODO: Desactivar perfil de estudiante
            DB::commit();

            return FormatearMensajeHelper::ok('Se eliminó la información', $data);
        } catch (Exception $e) {
            DB::rollback();

            return FormatearMensajeHelper::error($e);
        }
    }

    public function obtenerCursosPorMatricula($iYAcadId, Request $request)
    {
        try {
            $detallesCredencial = UsuariosService::obtenerDetallesCredencialEntidad($request->header('iCredEntPerfId'));
            $params = [Auth::user()->iPersId, $iYAcadId, $detallesCredencial->iSedeId, null];
            $matricula = MatriculasService::obtenerDetalleMatriculaEstudiante($params);
            $cursos = CompetenciaCurso::selCursosPorIe($matricula->iSedeId, $iYAcadId, $matricula->iNivelGradoId);

            return FormatearMensajeHelper::ok('Se eliminó la información', $cursos);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function obtenerMatriculasEstudiante($iEstudianteId, Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::APODERADO]]);
            $anioAcademico = YearAcademico::selYearAcademicoPorAnio($request->query('anio'));
            $request->merge(['iEstudianteId' => VerifyHash::decodesxId($iEstudianteId)]);
            $request->merge(['iYAcadId' => $anioAcademico->iYAcadId]);
            $data = MatriculasService::obtenerMatriculasEstudiante($request);

            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
