<?php

namespace App\Http\Controllers\api\acad;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarioAcademicosController extends Controller
{
    public function response($query)
    {
        $response = [
            'validated' => true,
            'message' => '',
            'data' => [],
        ];
        $estado = 200;

        try {
            $response['message'] = 'Se obtuvo la información';
            $response['data'] = $query;
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }

    public function selCalAcademicoSede(Request $request)
    {
        $query = collect(DB::select('EXEC grl.SP_SEL_DesdeTablaOVista ?,?,?,?', [
            'acad',
            'V_CalendariosAcademicos',
            '*',
            'ISedeId=' . $request->iSedeId,

        ]))->sortByDesc('cYearNombre')->values();

        return $this->response($query);
    }

    public function selCalAcademico(Request $request)
    {
        $query = DB::select('EXEC grl.SP_SEL_DesdeTablaOVista ?,?,?,?', [
            'acad',
            'V_CalendarioAcademico',
            '*',
            'iCalAcadId=' . $request->iCalAcadId,

        ])[0];

        return $this->response($query);
    }

    public function selFasesFechas()
    {
        $fasesPromQuery = DB::select(
            "EXEC grl.SP_SEL_DesdeTabla ?,?,?",
            [
                'acad',
                'fases_promocionales',
                'iFasePromId, cFasePromNombre',
            ]
        );

        $yearAcadQuery = DB::select(
            "EXEC grl.SP_SEL_DesdeTabla_Where ?,?,?,?",
            [
                'acad',
                'year_academicos',
                'iYAcadId, cYAcadNombre, dtYAcadInicio, dYAcadFin',
                "iEstado=1",
            ]
        )[0];

        return $this->response([
            'fasesProm' => $fasesPromQuery,
            'yearAcad' => $yearAcadQuery
        ]);
    }

    public function selDiasLaborales(Request $request){
        $query = DB::select(
            "EXEC acad.SP_SEL_stepCalendarioAcademicoDesdeJsonOpcion ?,?",
            [
                json_encode([
                    'jmod' => 'grl',
                    'jtable' => 'dias',
                ]),
                'getConsulta'
            ]
        );

        return $this->response($query);
    }

    public function selCalDiasLaborales(Request $request){
        $query = DB::select(
            "EXEC acad.SP_SEL_stepCalendarioAcademicoDesdeJsonOpcion ?,?",
            [
                json_encode([
                    'iCalAcadId' => $request->iCalAcadId,
                ]),
                'getCalendarioDiasLaborables'
            ]
        );

        return $this->response($query);
    }

    public function insCalDiasLaborales(Request $request){
        $query = DB::select(
            "EXEC acad.SP_INS_stepCalendarioAcademicoDesdeJsonOpcion ?,?",
            [
                $request->json,
                'addDiasLaborales'
            ]
        );

        return $this->response($query);
    }

    public function insCalAcademico(Request $request)
    {
        $query = DB::select("EXEC grl.SP_INS_EnTablaDesdeJSON ?,?,?", [
            'acad',
            'calendario_academicos',
            $request->calAcad,
        ]);

        return $this->response($query);
    }

    public function updCalAcademico(Request $request)
    {
        $query = DB::select("EXEC grl.SP_UPD_ParcheEnTablaDesdeJSON ?,?,?,?,?", [
            'acad',
            'calendario_academicos',
            'iCalAcadId',
            $request->iCalAcadId,
            $request->calAcad,
        ]);

        return $this->response($query);
    }

    public function updCalFaseFechas(Request $request)
    {
        $calFasesFechas = DB::select("EXEC grl.SP_UPD_EnTablaConJSON ?,?,?,?", [
            'acad',
            'calendario_academicos',
            $request->calAcademico,
            $request->iCalAcadId,
        ]);

        return $this->response([]);
    }

    public function selCalFasesProm(Request $request)
    {
        $query = DB::select(
            "EXEC grl.SP_SEL_DesdeTabla_Where ?,?,?,?",
            [
                'acad',
                'calendario_fases_promocionales',
                'iFaseId, iFasePromId, dtFaseInicio, dtFaseFin',
                'iCalAcadId=' . $request->iCalAcadId,
            ]
        );

        return $this->response($query);
    }

    public function insCalFasesProm(Request $request)
    {
        $query = DB::select("EXEC grl.SP_INS_EnTablaDesdeJSON ?,?,?", [
            'acad',
            'calendario_fases_promocionales',
            $request->calFasesProm,
        ]);

        return $this->response($query);
    }

    public function deleteCalFasesProm(Request $request)
    {
        $deleteCalFasesPromQuery = DB::select("EXEC grl.SP_DEL_RegistroConTransaccion ?,?,?,?,?", [
            'acad',
            'calendario_fases_promocionales',
            'iFaseId',
            $request->iFaseId,
            'calendario_periodos_evaluaciones'
        ]);

        return $this->response($deleteCalFasesPromQuery);
    }

    // !!! Eliminar? 
    public function searchCalAcademico(Request $request)
    {
        $solicitud = [
            $request->esquema,
            $request->tabla,
            $request->campos,
            $request->condicion
        ];
        //@json = N'[{	"jmod": "acad", "jtable": "calendario_academicos"}]'
        $query = DB::select(
            "EXEC grl.sp_SEL_DesdeTabla_Where ?,?,?,? ",
            $solicitud
        );

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 200;
        } catch (Exception $e) {
            $response = [
                'validated' => true,
                'message' => $e->getMessage(),
                'data' => [],
            ];

            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }
}
