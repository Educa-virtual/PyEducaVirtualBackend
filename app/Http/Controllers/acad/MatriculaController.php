<?php

namespace App\Http\Controllers\acad;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\acad\Matricula;
use App\Services\ParseSqlErrorService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;
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
        }
        catch (Exception $e) {
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
}
