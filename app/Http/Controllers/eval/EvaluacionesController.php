<?php

namespace App\Http\Controllers\eval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EvaluacionesController extends Controller
{

    public function obtenerEvaluacionesxiEvaluacionId(Request $request, $iEvaluacionId)
    {
        $request->merge(['iEvaluacionId' => $iEvaluacionId]);

        try {
            $fieldsToDecode = [
                'iEvaluacionId',
                'iTipoEvalId',
                'iCredId',
                'iEstudiante',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iEvaluacionId             ??  NULL,
                $request->iEstudiante             ??  NULL,
                $request->iCredId                   ??  NULL
            ];

            $data = DB::select(
                'exec eval.SP_SEL_evaluacionesxiEvaluacionId
                    @_iEvaluacionId=?,   
                    @_iEstudiante=?,   
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

    public function guardarEvaluaciones(Request $request)
    {
        // Validación de los parámetros de entrada
        $validator = Validator::make($request->all(), [
            'iDocenteId' => ['required'],
            'cEvaluacionTitulo' => ['required', 'max:250'],
            'cEvaluacionDescripcion' => ['required'],
            'dtEvaluacionInicio'     => ['required', 'date'],
            'dtEvaluacionFin'        => ['required', 'date', 'after:dtEvaluacionInicio'],

            'iContenidoSemId' => ['required'],
            'iActTipoId' => ['required'],
            'idDocCursoId' => ['required'],
        ], [
            'iDocenteId.required' => 'No se encontró el identificador iDocenteId',
            'cEvaluacionTitulo.required' => 'No se encontró el identificador cEvaluacionTitulo',
            'cEvaluacionTitulo.max' => 'El título no debe exceder los 250 caracteres.',
            'cEvaluacionDescripcion.required' => 'No se encontró una descripción',
            'dtEvaluacionInicio.required'     => 'La fecha y hora de inicio es obligatoria',
            'dtEvaluacionInicio.date'         => 'La fecha de inicio no es válida.',
            'dtEvaluacionFin.required'        => 'La fecha y hora de fin es obligatoria',
            'dtEvaluacionFin.date'            => 'La fecha de fin no es válida.',
            'dtEvaluacionFin.after'  => 'La fecha de fin debe ser posterior a la fecha de inicio.',

            'iContenidoSemId.required' => 'No se encontró el identificador iContenidoSemId',
            'iActTipoId.required' => 'No se encontró el identificador iActTipoId',
            'idDocCursoId.required' => 'No se encontró el identificador idDocCursoId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iTipoEvalId',
                'iInstrumentoId',
                'iEscalaCalifId',
                'iEvaluacionIdPadre',
                'iDocenteId',
                'iContenidoSemId',
                'iActTipoId',
                'idDocCursoId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iTipoEvalId               ?? NULL,
                $request->iDocenteId                ?? NULL,
                $request->cEvaluacionTitulo         ?? NULL,
                $request->cEvaluacionDescripcion    ?? NULL,
                $request->dtEvaluacionInicio        ?? NULL,
                $request->dtEvaluacionFin           ?? NULL,
                $request->cEvaluacionArchivoAdjunto ?? NULL,
                $request->iContenidoSemId           ?? NULL,
                $request->iActTipoId                ?? NULL,
                $request->idDocCursoId              ?? NULL,

                $request->iCredId                   ?? NULL
            ];

            $data = DB::select(
                'exec eval.SP_INS_evaluaciones 
                    @_iTipoEvalId=?,
                    @_iDocenteId=?,
                    @_cEvaluacionTitulo=?,
                    @_cEvaluacionDescripcion=?,
                    @_dtEvaluacionInicio=?,
                    @_dtEvaluacionFin=?,
                    @_cEvaluacionArchivoAdjunto=?,
                    @_iContenidoSemId=?,
                    @_iActTipoId=?,
                    @_idDocCursoId=?,
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iEvaluacionId > 0) {
                return new JsonResponse(
                    ['validated' => true, 'message' => 'Se ha guardado exitosamente ', 'data' => null],
                    Response::HTTP_OK
                );
            } else {
                return new JsonResponse(
                    ['validated' => false, 'message' => 'No se ha podido guardar', 'data' => null],
                    Response::HTTP_OK
                );
            }
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function actualizarEvaluacionesxiEvaluacionId(Request $request, $iEvaluacionId)
    {
        $request->merge(['iEvaluacionId' => $iEvaluacionId]);
        // Validación de los parámetros de entrada
        $validator = Validator::make($request->all(), [
            'iEvaluacionId' => ['required'],
            'cEvaluacionTitulo' => ['required', 'max:250'],
            'cEvaluacionDescripcion' => ['required'],
            'dtEvaluacionInicio'     => ['required', 'date'],
            'dtEvaluacionFin'        => ['required', 'date', 'after:dtEvaluacionInicio'],
        ], [
            'iEvaluacionId.required' => 'No se encontró el identificador iEvaluacionId',
            'cEvaluacionTitulo.required' => 'No se encontró el identificador cEvaluacionTitulo',
            'cEvaluacionTitulo.max' => 'El título no debe exceder los 250 caracteres.',
            'cEvaluacionDescripcion.required' => 'No se encontró una descripción',
            'dtEvaluacionInicio.required'     => 'La fecha y hora de inicio es obligatoria',
            'dtEvaluacionInicio.date'         => 'La fecha de inicio no es válida.',
            'dtEvaluacionFin.required'        => 'La fecha y hora de fin es obligatoria',
            'dtEvaluacionFin.date'            => 'La fecha de fin no es válida.',
            'dtEvaluacionFin.after'  => 'La fecha de fin debe ser posterior a la fecha de inicio.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iEvaluacionId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iEvaluacionId             ?? NULL,
                $request->cEvaluacionTitulo         ?? NULL,
                $request->cEvaluacionDescripcion    ?? NULL,
                $request->dtEvaluacionInicio        ?? NULL,
                $request->dtEvaluacionFin           ?? NULL,
                $request->cEvaluacionArchivoAdjunto ?? NULL,

                $request->iCredId                   ?? NULL
            ];

            $data = DB::select(
                'exec eval.SP_UPD_evaluacionesxiEvaluacionId
                    @_iEvaluacionId=?,
                    @_cEvaluacionTitulo=?,
                    @_cEvaluacionDescripcion=?,
                    @_dtEvaluacionInicio=?,
                    @_dtEvaluacionFin=?,
                    @_cEvaluacionArchivoAdjunto=?,
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iEvaluacionId > 0) {
                return new JsonResponse(
                    ['validated' => true, 'message' => 'Se ha actualizado exitosamente ', 'data' => null],
                    Response::HTTP_OK
                );
            } else {
                return new JsonResponse(
                    ['validated' => false, 'message' => 'No se ha podido actualizar', 'data' => null],
                    Response::HTTP_OK
                );
            }
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function eliminarEvaluacionesxiEvaluacionId(Request $request, $iEvaluacionId)
    {
        $request->merge(['iEvaluacionId' => $iEvaluacionId]);
        // Validación de los parámetros de entrada
        $validator = Validator::make($request->all(), [
            'iEvaluacionId' => ['required'],
        ], [
            'iEvaluacionId.required' => 'No se encontró el identificador iEvaluacionId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iEvaluacionId',
                'iCredId',
            ];

            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iEvaluacionId      ??  NULL,
                $request->iCredId            ??  NULL
            ];

            $data = DB::select(
                'exec eval.SP_DEL_evaluacionesxiEvaluacionId
                    @_iEvaluacionId=?, 
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iEvaluacionId > 0) {
                return new JsonResponse(
                    ['validated' => true, 'message' => 'Se ha eliminado exitosamente ', 'data' => null],
                    Response::HTTP_OK
                );
            } else {
                return new JsonResponse(
                    ['validated' => false, 'message' => 'No se ha podido eliminar', 'data' => null],
                    Response::HTTP_OK
                );
            }
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function obtenerReporteEstudiantesRetroalimentacion(Request $request)
    {
        // Validación de los parámetros de entrada
        $validator = Validator::make($request->all(), [
            'iIeCursoId' => ['required'],
            'iYAcadId' => ['required'],
            'iSedeId' => ['required'],
            'iSeccionId' => ['required'],
            'iNivelGradoId' => ['required'],
            'iEvaluacionId' => ['required'],
        ], [
            'iIeCursoId.required' => 'No se encontró el identificador iIeCursoId',
            'iYAcadId.required' => 'No se encontró el identificador iYAcadId',
            'iSedeId.required' => 'No se encontró el identificador iSedeId',
            'iSeccionId.required' => 'No se encontró el identificador iSeccionId',
            'iNivelGradoId.required' => 'No se encontró el identificador iNivelGradoId',
            'iEvaluacionId.required' => 'No se encontró el identificador iEvaluacionId'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $fieldsToDecode = [
            'iIeCursoId',
            'iYAcadId',
            'iSedeId',
            'iSeccionId',
            'iNivelGradoId',
            'iEvaluacionId'
        ];

        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->iIeCursoId            ??      NULL,
            $request->iYAcadId              ??      NULL,
            $request->iSedeId               ??      NULL,
            $request->iSeccionId            ??      NULL,
            $request->iNivelGradoId         ??      NULL,
            $request->iEvaluacionId         ??      NULL,
        ];

        try {
            // Ejecutar el procedimiento almacenado

            $data = DB::select(
                'EXEC [eval].[Sp_SEL_reporteEstudiantesRetroalimentacionxiEvaluacionId] 
                    @_iIeCursoId=?,
                    @_iYAcadId=?,
                    @_iSedeId=?,
                    @_iSeccionId=?,
                    @_iNivelGradoId=?,
                    @_iEvaluacionId=?',
                $parametros
            );
            // Preparar la respuesta
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = Response::HTTP_OK;

            return $response;
        } catch (\Exception $e) {
            // Manejo de excepción y respuesta de error
            $response = [
                'validated' => false,
                'message' => $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine(),
                'data' => [],
            ];
            $estado = Response::HTTP_INTERNAL_SERVER_ERROR;
            return new JsonResponse($response, $estado);
        }
    }

    public function handleCrudOperation(Request $request)
    {
        $parametros = $this->validateRequest($request);

        try {
            switch ($request->opcion) {
                case 'CONSULTARxiEvaluacionId':
                    $data = DB::select('exec eval.Sp_SEL_evaluaciones ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    $data = $this->encodeId($data);
                    return new JsonResponse(
                        ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data],
                        200
                    );
                    break;
                case 'GUARDARxProgActxiEvaluacionId':
                    $data = DB::select('exec eval.Sp_INS_evaluaciones ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iEvaluacionId > 0) {
                        $data = $this->encodeId($data);
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'Se guardó la información', 'data' => $data],
                            200
                        );
                    } else {
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'No se ha podido guardar la información', 'data' => null],
                            500
                        );
                    }
                case 'ELIMINAR':
                    $data = DB::select('exec eval.Sp_DEL_evaluaciones ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iEvaluacionId > 0) {
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'Se eliminó la información', 'data' => null],
                            200
                        );
                    } else {
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'No se ha podido eliminar la información', 'data' => null],
                            500
                        );
                    }
                case 'ACTUALIZARxProgActxiEvaluacionId':
                    $data = DB::select('exec eval.Sp_UPD_evaluaciones ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iEvaluacionId > 0) {
                        $data = $this->encodeId($data);
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'Se actualizó la información', 'data' => $data],
                            200
                        );
                    } else {
                        return new JsonResponse(
                            ['validated' => true, 'message' => 'No se ha podido actualizar la información', 'data' => null],
                            500
                        );
                    }
            }
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => $e->getMessage(), 'data' => []],
                500
            );
        }
    }

    public function generarListaEstudiantesSedeSeccionGrado(Request $request)
    {
        try {

            $parametros = [
                $request->iSedeId ??  NULL,
                $request->iSeccionId ??  NULL,
                $request->iYAcadId ??  NULL,
                $request->iNivelGradoId ??  NULL,
            ];

            $data = DB::select(
                'exec aula.SP_SEL_listarEstudiantesSedeSeccionYAcad   
                   	@iSedeId=?,
                    @iSeccionId=?,
                    @iYAcadId=?,
                    @iNivelGradoId=?,
                $parametros'
            );

            //$data = VerifyHash::encodeRequest($data, $fieldsToDecode);

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
    public function obtenerPeriodosEvaluacion(Request $request)
    {
        try {
            $data = DB::select(
                'SELECT c.iPeriodoEvalId, iPeriodoEvalCantidad, c.*  
             FROM acad.calendario_academicos c 
             INNER JOIN acad.periodo_evaluaciones p ON p.iPeriodoEvalId = c.iPeriodoEvalId'
            );

            return new JsonResponse(
                ['validated' => true, 'message' => 'Períodos obtenidos exitosamente', 'data' => $data],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => $e->getMessage(), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
