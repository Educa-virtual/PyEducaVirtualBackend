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
            "EXEC grl.SP_SEL_DesdeTablaOVista ?,?,?,?",
            [
                'acad',
                'V_YearAcademico',
                'iYAcadId, cYAcadNombre, dtYAcadInicio, dYAcadFin',
                "iYearEstado=1",
            ]
        )[0];

        return $this->response([
            'fasesProm' => $fasesPromQuery,
            'yearAcad' => $yearAcadQuery
        ]);
    }

    public function selTurnosModalidades()
    {
        $turnosQuery = DB::select(
            "EXEC grl.SP_SEL_DesdeTabla ?,?,?",
            [
                'acad',
                'turnos',
                'iTurnoId, cTurnoNombre',
            ]
        );

        $modalidadesQuery = DB::select(
            "EXEC grl.SP_SEL_DesdeTabla ?,?,?",
            [
                'acad',
                'modalidad_servicios',
                'iModalServId, cModalServNombre',
            ]
        );

        return $this->response([
            'turnos' => $turnosQuery,
            'modalidades' => $modalidadesQuery
        ]);
    }

    public function selDiasLaborales(Request $request)
    {
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

    public function selCalDiasLaborales(Request $request)
    {
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

    public function insCalDiasLaborales(Request $request)
    {
        $query = DB::select(
            "EXEC acad.SP_INS_stepCalendarioAcademicoDesdeJsonOpcion ?,?",
            [
                $request->json,
                'addDiasLaborales'
            ]
        );

        return $this->response($query);
    }

    public function insCalFormasAtencion(Request $request)
    {
        $query = DB::select(
            "EXEC acad.SP_INS_stepCalendarioAcademicoDesdeJsonOpcion ?,?",
            [
                $request->json,
                'addCalTurno'
            ]
        );

        return $this->response($query);
    }

    public function insCalAcademico(Request $request)
    {
        $query = DB::select("EXEC acad.SP_INS_stepCalendarioAcademicoDesdeJsonOpcion ?,?", [
            $request->json,
            'addCalAcademico',
        ]);

        return $this->response($query);
    }

    public function updCalAcademico(Request $request)
    {
        $query = DB::select("EXEC acad.SP_UPD_stepCalendarioAcademicoDesdeJsonOpcion ?,?", [
            $request->calAcad,
            // 'acad',
            'updateCalAcademico',
            // 'iCalAcadId',
            // $request->iCalAcadId,
        ]);

        return $this->response($query);
    }

    public function updCalFaseFechas(Request $request)
    {
        $calFasesFechas = DB::select("EXEC grl.SP_UPD_EnTablaConJSON ?,?,?,?", [
            'acad',
            'calendario_academicos',
            $request->calAcademico,
            $request->iCalAcadId
        ]);

        return $this->response([]);
    }

    public function updCalFormasAtencion(Request $request)
    {
        $query = DB::statement("EXEC acad.SP_UPD_stepCalendarioAcademicoDesdeJsonOpcion ?,?", [
            $request->json,
            'updateCalTurno',
        ]);

        return $this->response(['message' => 'Registros actualizado exitosamente']);
    }

    public function updCalFasesProm(Request $request)
    {
        $query = DB::statement("EXEC acad.SP_UPD_stepCalendarioAcademicoDesdeJsonOpcion ?,?", [
            $request->calFases,
            'updateCalFase',
        ]);

        return $this->response(['message' => 'Registros actualizado exitosamente']);
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
        $query = DB::select("EXEC acad.SP_INS_stepCalendarioAcademicoDesdeJsonOpcion ?,?", [
            $request->calFasesProm,
            'addCalFase',
            // 'acad',
            // 'calendario_fases_promocionales',
        ]);

        return $this->response($query);
    }

    public function insCalPeriodosFormativos(Request $request)
    {
        $query = DB::statement("EXEC acad.SP_INS_stepCalendarioAcademicoDesdeJsonOpcion ?,?", [
            $request->json,
            'addCalPeriodoEval',
        ]);

        return $this->response($query);
    }

    public function deleteCalDiasLaborales(Request $request)
    {
        $query = DB::statement("EXEC acad.SP_DEL_stepCalendarioAcademicoDesdeJsonOpcion ?,?", [
            $request->calDiasLaborales,
            'deleteDiasLaborales',
        ]);


        return $this->response(['message' => 'Registros eliminados exitosamente']);
    }

    public function deleteCalFasesProm(Request $request)
    {
        $deleteCalFasesPromQuery = DB::select("EXEC acad.SP_DEL_stepCalendarioAcademicoDesdeJsonOpcion ?,?", [
            $request->deleteFasesProm,
            'deleteCalFasesProm',
            // 'iFaseId',
            // 'calendario_periodos_evaluaciones'
        ]);

        return $this->response($deleteCalFasesPromQuery);
    }

    public function selDias(Request $request){
        $diasQuery = DB::select(
            "EXEC grl.SP_SEL_DesdeTabla ?,?,?",
            [
                'grl',
                'dias',
                'iDiaId, iDia, cDiaNombre, cDiaAbreviado',
            ]
        );

        return $this->response($diasQuery);
    }

    public function selPeriodosFormativos(){
        $periodosQuery = DB::select(
            "EXEC grl.SP_SEL_DesdeTabla ?,?,?",
            [
                'acad',
                'periodo_evaluaciones',
                'iPeriodoEvalId, cPeriodoEvalNombre, cPeriodoEvalLetra, iPeriodoEvalCantidad',
            ]
        );

        return $this->response($periodosQuery); 
    }
    
    public function deleteCalFormasAtencion(Request $request)
    {
        $deleteCalFormaAtencionQuery = DB::statement("EXEC acad.SP_DEL_stepCalendarioAcademicoDesdeJsonOpcion ?,?", [
            $request->json,
            'deleteCalTurno',
        ]);

        return $this->response($deleteCalFormaAtencionQuery);
    }




    // no tocar

    public function updateCalAcademico(Request $request)
    {
        $solicitud = [
            $request->json,
            $request->_opcion
        ];

        $query = DB::select(
            "EXEC acad.SP_UPD_stepCalendarioAcademicoDesdeJsonOpcion ?,?", //actualizado
            $solicitud
        );

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 201;
        } catch (Exception $e) {
            $response = [
                'validated' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ];
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }
    public function deleteCalAcademico(Request $request)
    {
        $solicitud = [
            $request->json,
            $request->_opcion
        ];

        $query = DB::select(
            "EXEC acad.SP_DEL_stepCalendarioAcademicoDesdeJsonOpcion ?,?", //actualizado
            $solicitud
        );

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 201;
        } catch (Exception $e) {
            $response = [
                'validated' => false,
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
            $request->_opcion
        ];

        $query = DB::select(
            "EXEC acad.SP_INS_stepCalendarioAcademicoDesdeJsonOpcion ?,?", //actualizado
            $solicitud
        );

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 201;
        } catch (Exception $e) {
            $response = [
                'validated' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ];
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }

    public function searchAcademico(Request $request)
    {
        $solicitud = [
            $request->json,
            $request->_opcion
        ];

        $query = DB::select(
            "EXEC acad.SP_SEL_stepCalendarioAcademicoDesdeJsonOpcion ?,?", //actualizado
            $solicitud
        );

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 201;
        } catch (Exception $e) {
            $response = [
                'validated' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ];
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }

    public function deleteCalPeriodosFormativos(Request $request){
        $deleteCalPeriodosAcademicos = DB::statement("EXEC acad.SP_DEL_stepCalendarioAcademicoDesdeJsonOpcion ?,?", [
            $request->json,
            'deleteCalPeriodo',
        ]);

        return $this->response($deleteCalPeriodosAcademicos);
    }

    public function addAmbienteAcademico(Request $request)
    {
        $solicitud = [
            $request->json,
            $request->_opcion
        ];

        $query = DB::select(
            "EXEC acad.SP_INS_stepAmbienteAcademicoDesdeJsonOpcion ?,?",  //Actualizado
            $solicitud
        );

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información de los ambientes',
                'data' => $query,
            ];

            $estado = 201;
        } catch (Exception $e) {
            $response = [
                'validated' => false,
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
        //@json = N'[{  "jmod": "acad", "jtable": "calendario_academicos"}]'
        $query = DB::select(
            "EXEC grl.sp_SEL_DesdeTabla_Where ?,?,?,? ",
            $solicitud
        );

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información de los calendarios académicos',
                'data' => $query,
            ];

            $estado = 200;
        } catch (Exception $e) {
            $response = [
                'validated' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ];

            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }

    public function selAmbienteAcademico(Request $request)
    {
        $solicitud = [
            $request->json,
            $request->_opcion
        ];

        $query = DB::select(
            "EXEC acad.SP_SEL_stepAmbienteAcademicoDesdeJsonOpcion ?,?",  // actualizado
            $solicitud
        );

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información de los ambientes',
                'data' => $query,
            ];

            $estado = 201;
        } catch (Exception $e) {
            $response = [
                'validated' => false,
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
            $request->_opcion
        ];

        //@json = N'[{  "jmod": "acad", "jtable": "calendario_academicos"}]'
        $query = DB::select(
            "EXEC grl.SP_INS_TablaYearXopcion ?,?",
            $solicitud
        );
        //  [$json, $opcion ]);

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 201;
        } catch (Exception $e) {
            $response = [
                'validated' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ];

            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }

    public function updateYear(Request $request)
    {
        $solicitud = [
            $request->json,
            $request->_opcion
        ];

        //@json = N'[{  "jmod": "acad", "jtable": "calendario_academicos"}]'
        $query = DB::select(
            "EXEC grl.SP_UPD_TablaYearXopcion ?,?",
          
            $solicitud
        );
        //  [$json, $opcion ]);

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query
            ];

            $estado = 201;
        } catch (Exception $e) {
            $response = [
                'validated' => false,
                'message' => $e->getMessage(),
                'data' => []
            ];

            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }

    public function deleteYear(Request $request)
    {
        //    $json = json_encode($request->json);
        //    $opcion = $request->_opcion;

        $solicitud = [
            $request->json,
            $request->_opcion,
        ];

        //@json = N'[{  "jmod": "acad", "jtable": "calendario_academicos"}]'
        $query = DB::select(
            "EXEC grl.SP_DEL_TablaYearXopcion ?,?",
            $solicitud
        );
        //  [$json, $opcion ]);

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 201;
        } catch (Exception $e) {
            $response = [
                'validated' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ];

            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }


    public function updateCalendario(Request $request)
    {
        //    $json = json_encode($request->json);
        //    $opcion = $request->_opcion;
        $condiciones = json_encode(
            [
                'COLUMN_NAME' => $request->campo,
                'VALUE' => $request->condicion
            ]
        );

        $solicitud = [
            $request->esquema,     // NVARCHAR(128),          -- Esquema de la tabla
            $request->tabla,     // NVARCHAR(128),           -- Nombre de la tabla
            $request->json,  // NVARCHAR(MAX),       -- Datos en formato JSON para la actualización
            $condiciones // NVARCHAR(MAX)  -- JSON con condiciones para el WHERE (Array de condiciones AND)
        ];

        //@json = N'[{  "jmod": "acad", "jtable": "calendario_academicos"}]'
        $query = DB::select(
            "EXEC grl.SP_UPD_EnTablaConJSON ?,?,?,?",
            $solicitud
        );
        //  [$json, $opcion ]);

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 201;
        } catch (Exception $e) {
        $response = [
            'validated' => false,
            'message' => $e->getMessage(),
            'data' => [],
        ];
        
        $estado = 500;
        }
        return $response;
    }

    public function deleteCalendario(Request $request)
    {
        //    $json = json_encode($request->json);
        //    $opcion = $request->_opcion;

        $solicitud = [
            $request->esquema, //NVARCHAR(128),       -- Nombre del esquema
            $request->tabla, // NVARCHAR(128),   -- Nombre de la tabla principal
            $request->campo, //NVARCHAR(128),       -- Nombre del campo ID de la tabla principal
            $request->valorId, // BIGINT,              -- Valor del ID a eliminar
            // $TablaHija = null //NVARCHAR(128) = NULL   -- Nombre de la tabla hija (opcional)        
        ];

        //@json = N'[{  "jmod": "acad", "jtable": "calendario_academicos"}]'
        $query = DB::select(
            "EXEC grl.SP_DEL_RegistroConTransaccion ?,?,?,?",
            $solicitud
        );
        //  [$json, $opcion ]);

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 201;
        } catch (Exception $e) {
            $response = [
                'validated' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ];

            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }


    public function searchGradoCiclo(Request $request)
    {
    //    $json = json_encode($request->json);
    //    $opcion = $request->_opcion;

        $solicitud = [
            $request-> iNivelTipoId, //NVARCHAR(128),       -- Nombre del esquema        
        ];
        
        //@json = N'[{  "jmod": "acad", "jtable": "calendario_academicos"}]'
        $query = DB::select("EXEC acad.SP_SEL_generarGradosSeccionesCiclosXiNivelTipoId ?", $solicitud);
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
            'validated' => false,
            'message' => $e->getMessage(),
            'data' => [],
        ];
        
        $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }
}



