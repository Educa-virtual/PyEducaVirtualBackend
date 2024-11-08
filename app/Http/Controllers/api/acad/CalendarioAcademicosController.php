<?php

namespace App\Http\Controllers\api\acad;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarioAcademicosController extends Controller
{
    public function addCalendarioFasesAcad(Request $request)
    {
        $query = DB::select("EXEC grl.SP_INS_EnTablaMaestroDetalleDesdeJSON ?,?,?,?,?,?", [
            'acad',
            'calendario_academicos',
            $request->calAcad,
            'calendario_fases_promocionales',
            $request->calFaseProm,
            'iCalAcadId'
        ]);
    }

    public function selFasesYear()
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

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => ['fasesProm' => $fasesPromQuery, 'yearAcad' => $yearAcadQuery],
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

    public function addCalAcademico(Request $request)
    {
        $solicitud = [
            $request->json,
            $request->_opcion,
        ];

        $query = DB::select(
            "EXEC acad.SP_ACAD_CRUD_CALENDARIO ?,?",
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

    public function selCalAcademico(Request $request)
    {
        $solicitud = [
            $request->json,
            $request->_opcion,
        ];
        //@json = N'[{	"jmod": "acad", "jtable": "calendario_academicos"}]'
        $query = DB::select(
            "EXEC acad.Sp_ACAD_CRUD_CALENDARIO ?,?",
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
    public function addYear(Request $request)
    {

        //    $json = json_encode($request->json);
        //    $opcion = $request->_opcion;

        $solicitud = [
            $request->json,
            $request->_opcion,
        ];

        //@json = N'[{	"jmod": "acad", "jtable": "calendario_academicos"}]'
        $query = DB::select(
            "EXEC grl.Sp_CRUD_YEAR ?,?",
            $solicitud
        );
        //  [$json, $opcion ]);

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
