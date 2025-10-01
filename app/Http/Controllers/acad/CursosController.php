<?php

namespace App\Http\Controllers\acad;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
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
        $request->validate(
            [
                'opcion' => 'required',
            ],
            [
                'opcion.required' => 'Hubo un problema al obtener la acción',
            ]
        );
        if ($request->iCursoId) {
            $iCursoId = $this->hashids->decode($request->iCursoId);
            $iCursoId = count($iCursoId) > 0 ? $iCursoId[0] : $iCursoId;
        }

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iCursoId                                              ??  NULL,
            $request->iCurrId                                      ??  NULL,
            $request->iTipoCursoId                                 ??  NULL,
            $request->cCursoNombre                                 ??  NULL,
            $request->nCursoCredTeoria                             ??  NULL,
            $request->nCursoCredPractica                           ??  NULL,
            $request->cCursoDescripcion                            ??  NULL,
            $request->nCursoTotalCreditos                          ??  NULL,
            $request->cCursoPerfilDocente                          ??  NULL,
            $request->iCursoTotalHoras                             ??  NULL,
            $request->iCursoEstado                                 ??  NULL,
            $request->iEstado                                      ??  NULL,
            $request->iSesionId                                    ??  NULL,

            $request->iCredId

        ];

        try {
            $data = DB::select('exec acad.Sp_SEL_cursos
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
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
            Gate::authorize('tiene-perfil', [[Perfil::ESTUDIANTE]]); //$iCredPerfIdEstudiante, $iYAcadId, $iIeCursoId
            $data =  ReportesAcademicosService::obtenerResultadoParaGrafico($request->header('iCredEntPerfId'), $iYAcadId, $iIeCursoId);
            return FormatearMensajeHelper::ok("Datos obtenidos", $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
