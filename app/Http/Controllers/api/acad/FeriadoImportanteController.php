<?php

namespace App\Http\Controllers\api\acad;

use App\Helpers\CollectionStrategy;
use App\Helpers\ResponseHandler;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FeriadoImportanteController extends Controller
{
    const schema = "acad";

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

    public function insFechasImportantes(Request $request)
    {
        $query = DB::select("EXEC acad.SP_INS_stepCalendarioAcademicoDesdeJsonOpcion @json = :json, @_opcion = :opcion", [
            'json' => json_encode([
                'iFechaImpId' => $request->input('iFechaImpId') ?? NULL,
                'iTipoFerId' => $request->input('iTipoFerId') ?? 1,
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

    public function updFechasImportantes(Request $request)
    {
        $query = DB::select("EXEC acad.SP_INS_stepCalendarioAcademicoDesdeJsonOpcion @json = :json, @_opcion = :opcion", [
            'json' => json_encode([
                'iFechaImpId' => $request->input('iFechaImpId'),
                'iTipoFerId' => $request->input('iTipoFerId') ?? 1,
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
