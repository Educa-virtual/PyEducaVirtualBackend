<?php

namespace App\Http\Controllers\api\acad;

use App\Enums\Perfil;
use App\Helpers\CollectionStrategy;
use App\Helpers\FormatearMensajeHelper;
use App\Helpers\ResponseHandler;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\acad\FechasImportantesRequest;
use App\Models\acad\FechaImportante;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class FeriadoImportanteController extends Controller
{
    const schema = "acad";

    public function selFechasImportantes(Request $request)
    {
       try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);
            $query = FechaImportante::selFechasImportantes($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $query);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function selDependenciaFechas(Request $request)
    {
       try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);
            $query = FechaImportante::selDependenciaFechas($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $query);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function getFechasImportantes(Request $request)
    {
        try {
            $iYAcadId = $request->route('iYAcadId');
            $iSedeId = $request->route('iSedeId');

            $query = DB::select('EXEC acad.SP_SEL_stepCalendarioAcademicoDesdeJsonOpcion @json = :json, @_opcion = :opcion', [
                'json' => json_encode([
                    'iSedeId' => $iSedeId,
                    'iYAcadId' => $iYAcadId,
                ]),
                'opcion' => 'getCalendarioFechas'
            ]);

            return ResponseHandler::success($query, 'Fechas importantes obtenidas correctamente.');
        } catch (\Exception $e) {
            return ResponseHandler::error(
                'Error al obtener los feriados nacionales.',
                500,
                $e->getMessage()
            );
        }
    }

    public function getDependenciaFechas(Request $request)
    {
        try {
            $query = DB::select('EXEC acad.SP_SEL_stepCalendarioAcademicoDesdeJsonOpcion @json = :json, @_opcion = :opcion', [
                'json' => json_encode([
                    'iFechaImpId' => $request->route('iFechaImpId'),
                ]),
                'opcion' => 'getDependenciaFechas'
            ]);

            return ResponseHandler::success($query, 'Fechas importantes obtenidas correctamente.');
        } catch (\Exception $e) {
            return ResponseHandler::error(
                'Error al obtener los feriados nacionales.',
                500,
                $e->getMessage()
            );
        }
    }

    public function insFechasImportantes(FechasImportantesRequest $request)
    {   
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);

            $parametros = [
                    $request->iFechaImpId ?? NULL,
                    $request->iTipoFerId ?? NULL,
                    $request->iCalAcadId ?? NULL,
                    $request->bFechaImpSeraLaborable ?? NULL,
                    $request->cFechaImpNombre ?? NULL,
                    $request->dtFechaImpFecha ?? NULL,
                    $request->cFechaImpURLDocumento ?? NULL,
                    $request->cFechaImpInfoAdicional ?? NULL,
                    $request->iDepFechaImpId ?? NULL,
                    $request->iCredEntPerfId ?? NULL,
            ];
            
            $cantidad = str_repeat('?,', count($parametros) - 1) . '?';
            $query = DB::select("EXEC acad.Sp_INS_FechasEspecialesIE ". $cantidad, $parametros);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $query);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function delFechasImportantes(Request $request)
    {

        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);

            $iFechaImpId = $request->route('iFechaImpId');
            $query = FechaImportante::delFechasImportantes($iFechaImpId);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $query);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }

    }

    public function updFechasImportantes(Request $request)
    {
        $query = DB::select("EXEC acad.SP_INS_stepCalendarioAcademicoDesdeJsonOpcion @json = :json, @_opcion = :opcion", [
            'json' => json_encode([
                'iFechaImpId' => $request->input('iFechaImpId'),
                'iTipoFerId' => 4,
                'iCalAcadId' => $request->input('iCalAcadId'),
                'bFechaImpSeraLaborable' => $request->input('bFechaImpSeraLaborable'),
                'cFechaImpNombre' => $request->input('cFechaImpNombre'),
                'dtFechaImpFecha' => $request->input('dtFechaImpFecha'),
                'cFechaImpURLDocumento' => $request->input('cFechaImpURLDocumento'),
                'cFechaImpInfoAdicional' => $request->input('cFechaImpInfoAdicional'),
            ]),
            'opcion' => 'addFechasEspecialesIE'
        ]);

        return ResponseHandler::success($query, 'Fechas importantes obtenidas correctamente.');
    }

    public function deleteFechasImportantes(Request $request)
    {
        $iFechaImpId = $request->route('iFechaImpId');

        $query = DB::select("EXEC acad.SP_DEL_stepCalendarioAcademicoDesdeJsonOpcion @json = :json, @_opcion = :opcion", [
            'json' => json_encode([
                'iFechaImpId' => $iFechaImpId
            ]),
            'opcion' => 'deleteFechasEspeciales'
        ]);

        return ResponseHandler::success($query, 'Fechas importantes obtenidas correctamente.');
    }
}
