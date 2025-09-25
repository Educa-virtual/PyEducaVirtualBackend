<?php

namespace App\Http\Controllers\acad;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;

class PeriodoEvaluacionesController extends Controller
{
    const schema = 'acad';

    public function getPeriodoEvaluaciones(Request $request)
    {
        $query = DB::select("EXEC grl.SP_SEL_DesdeTablaOVista @nombreEsquema = :esquema, @nombreObjeto = :tabla, @campos = :campos, @condicionWhere = :where", [
            'esquema' => self::schema,
            'tabla' => 'periodo_evaluaciones',
            'campos' => '*',
            'where' => '1=1',

        ]);

        return ResponseHandler::success($query, 'Periodos de evaluación obtenidos correctamente.');
    }

    public function processConfigCalendario(Request $request)
    {
        $query = DB::select("EXEC acad.Sp_INS_generarDistribucionSemanasXiYearIdXiPerioEvalId @iPerioEvalId = :iPerioEvalId, @iYAcadId = :iYAcadId", [
            'iPerioEvalId' => $request->input('iPerioEvalId'),
            'iYAcadId' => $request->input('iYAcadId'),
        ]);


        return ResponseHandler::success($query, 'Calendario procesado correctamente.');
    }

    public function obtenerPeriodoEvaluaciones(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iCredId',
            ];

            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCredId                   ??  NULL
            ];

            $data = DB::select(
                'exec acad.SP_SEL_periodoEvaluaciones
                    @_iCredId=?',
                $parametros
            );

            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);

            return new JsonResponse(
                ['validated' => true, 'message' => 'Se ha obtenido exitosamente ', 'data' => $data],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
