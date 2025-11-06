<?php

namespace App\Http\Controllers\acad;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use App\Models\acad\CompetenciaCurso;
use App\Models\acad\Matricula;
use App\Models\acad\YearAcademico;
use App\Services\acad\MatriculasService;
use App\Services\ParseSqlErrorService;
use App\Services\seg\UsuariosService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class MatriculaController extends Controller
{
    protected $hashids;
    protected $parseSqlErrorService;

    public function __construct()
    {
        $this->hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
        $this->parseSqlErrorService = new ParseSqlErrorService;
    }

    /**
     * Busca grados, secciones y turnos configurados en el año para el colegio
     * @param Request $request con los parametros
     * @return JsonResponse { {is_validated, status_message, data}, status_code }
     */
    public function searchGradoSeccionTurnoConf(Request $request)
    {
        try {
            $data = Matricula::selGradoSeccionTurnoConf($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    /**
     * Lista todos los grados
     * @param Request $request
     * @return JsonResponse
     */
    public function searchNivelGrado(Request $request)
    {
        try {
            $data = Matricula::selNivelGrado($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    /**
     * Determina el grado de un estudiante segun matriculas pasadas
     * @param Request $request con los parametros
     * @return JsonResponse { {is_validated, status_message, data}, status_code }
     */
    public function determinarGradoEstudiante(Request $request)
    {
        try {
            $data = Matricula::selDeterminarGradoEstudiante($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    /**
     * Guarda una matricula
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request)
    {
        try {
            $data = Matricula::insMatricula($request);
            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    /**
     * Busca matriculas segun parametros
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $data = Matricula::selMatriculas($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    /**
     * Busca una matricula segun parametros
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request)
    {
        try {
            $data = Matricula::selMatricula($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    /**
     * Elimina una matricula
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        try {
            $data = Matricula::delMatriculaPorId($request);
            return FormatearMensajeHelper::ok('Se eliminó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function obtenerCursosPorMatricula($iYAcadId, Request $request)
    {
        try {
            $detallesCredencial = UsuariosService::obtenerDetallesCredencialEntidad($request->header('iCredEntPerfId'));
            $params = [Auth::user()->iPersId, $iYAcadId, $detallesCredencial->iSedeId, NULL];
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
