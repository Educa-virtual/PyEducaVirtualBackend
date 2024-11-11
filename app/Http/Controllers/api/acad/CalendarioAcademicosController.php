<?php

namespace App\Http\Controllers\api\acad;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarioAcademicosController extends Controller
{
    public function response($query){
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

        $response = [
            'validated' => true,
            'message' => '',
            'data' => [],
        ];
        $estado = 200;
        
        if (!$request->iSedeId) {
            $response['message'] = 'Error al solicitar calendarios académicos';
            $estado = 500;
        } else {
            try {
                $response['message'] = 'Se obtuvo la información';
                $response['data'] = $query;
            } catch (Exception $e) {
                $response['message'] = $e->getMessage();
                $estado = 500;
            }
        }
        
        return new JsonResponse($response, $estado);
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
            'fasesProm' => $fasesPromQuery, 'yearAcad' => $yearAcadQuery
        ]);
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

    public function updCalFaseFechas(Request $request)
    {
        $calFasesFechas = DB::select("EXEC grl.SP_UPD_EnTablaConJSON ?,?,?,?", [
            'acad',
            'calendario_academicos',
            $request->calAcademico,
            $request->iCalAcadId,
        ]);

        return $this->response([

        ]);
    }

    public function insCalFasesProm(Request $request){
        
    }

    public function deleteCalFasesProm(Request $request){
        $calFasesProm = DB::select("EXEC grl.SP_DEL_RegistroConTransaccion ?,?,?,?,?,?", [
            'acad',
            'calendario_fases_promocionales',
            'iFaseId',
            $request->iFaseId,
            'calendario_periodos_evaluaciones'
        ]);

        return $this->response([

        ]);
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
