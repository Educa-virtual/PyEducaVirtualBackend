<?php

namespace App\Http\Controllers\acad;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\acad\Curso;
use App\Services\acad\ReportesAcademicosService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CursosController extends Controller
{
    protected $hashids;
    protected $iCursoId;

    public function __construct()
    {
        $this->hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
    }

    public function list(Request $request)
    {
        try {
            if ($request->iCursoId) {
                $iCursoId = $this->hashids->decode($request->iCursoId);
                $iCursoId = count($iCursoId) > 0 ? $iCursoId[0] : null;
            }
            $data = Curso::selCursos($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function listarCursosPorNivel(Request $request)
    {
        if ($request->query('nivel') == '0') {
            $solicitud = [
                '{"id":"1289"}', //Número cualquiera
                'getCursoNivelGrado'
            ];
            $data = DB::select(
                "EXEC acad.SP_SEL_stepCalendarioAcademicoDesdeJsonOpcion ?,?",
                $solicitud
            );
        } else {
            $data = DB::select('[acad].SP_SEL_CursosXiNivelTipoId @iNivelTipoId=?', [$request->query('nivel')]);
        }

        return response()->json(['status' => 'Success', 'message' => 'Datos obtenidos', 'data' => $data], Response::HTTP_OK);
    }

    public function obtenerResultadoParaGrafico($iYAcadId, $iIeCursoId, Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ESTUDIANTE]]);
            $data =  ReportesAcademicosService::obtenerResultadoParaGrafico($request->header('iCredEntPerfId'), $iYAcadId, $iIeCursoId);
            return FormatearMensajeHelper::ok("Datos obtenidos", $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
