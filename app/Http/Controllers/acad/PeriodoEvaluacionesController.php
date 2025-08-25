<?php

namespace App\Http\Controllers\acad;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
