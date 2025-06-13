<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ForosController extends Controller
{

    public function obtenerForoxiForoId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iForoId' => ['required'],
        ], [
            'iForoId.required' => 'No se encontró el identificador iForoId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $fieldsToDecode = [
            'iForoId'
        ];
        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->iForoId,
        ];

        try {
            $data = DB::select('exec aula.SP_SEL_Foro
                ?', $parametros);

            $response = ['validated' => true, 'mensaje' => 'Se octuvo la información exitosamente.', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function actualizarForo(Request $request)
    {
        // return $request ->all();
        $validator = Validator::make($request->all(), [
            'iForoId' => ['required'],
        ], [
            'iForoId.required' => 'No se encontró el identificador iForoId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $fieldsToDecode = [
            'iForoId',
            'iForoCatId',
            'iDocenteId'
        ];
        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->iForoId,
            $request->iForoCatId,
            $request->iDocenteId,
            $request->cForoTitulo,
            $request->cForoDescripcion,
            $request->dtForoPublicacion,
            $request->dtForoInicio,
            $request->dtForoFin,
        ];

        try {
            $data = DB::select(
                'exec aula.SP_UPD_foro 
                    @_iForoId=?, 
                    @_iForoCatId=?, 
                    @_iDocenteId=?, 
                    @_cForoTitulo=?, 
                    @_cForoDescripcion=?, 
                    @_dtForoPublicacion=?, 
                    @_dtForoInicio=?, 
                    @_dtForoFin=?',
                $parametros
            );

            return $data;
            if ($data[0]->iForoId > 0) {
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

    public function eliminarxiForoId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iForoId' => ['required'],
        ], [
            'iForoId.required' => 'No se encontró el identificador iForoId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $fieldsToDecode = [
            'iForoId'
        ];
        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->opcion            ??      NULL,
            $request->valorBusqueda     ??      NULL,
            $request->iForoId           ??      NULL
        ];

        try {
            $data = DB::select('exec aula.SP_DEL_foros
               ?,?,?', $parametros);

            if ($data[0]->iForoId > 0) {

                $response = ['validated' => true, 'mensaje' => 'Se eliminó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function obtenerListaEstudiantes(Request $request)
    {
        // Validación de los parámetros de entrada
        $validator = Validator::make($request->all(), [
            'iIeCursoId' => ['required'],
            'iYAcadId' => ['required'],
            'iSedeId' => ['required'],
            'iSeccionId' => ['required'],
            'iNivelGradoId' => ['required']
        ], [
            'iIeCursoId.required' => 'No se encontró el identificador iIeCursoId',
            'iYAcadId.required' => 'No se encontró el identificador iYAcadId',
            'iSedeId.required' => 'No se encontró el identificador iSedeId',
            'iSeccionId.required' => 'No se encontró el identificador iSeccionId',
            'iNivelGradoId.required' => 'No se encontró el identificador iNivelGradoId'
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
            'iNivelGradoId'
        ];

        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->iIeCursoId            ??      NULL,
            $request->iYAcadId              ??      NULL,
            $request->iSedeId               ??      NULL,
            $request->iSeccionId            ??      NULL,
            $request->iNivelGradoId         ??      NULL
        ];

        try {
            // Ejecutar el procedimiento almacenado

            $data = DB::select(
                'EXEC [acad].[Sp_SEL_reporteFinalDeNotas] 
                    @_iIeCursoId=?,
                    @_iYAcadId=?,
                    @_iSedeId=?,
                    @_iSeccionId=?,
                    @_iNivelGradoId=?',
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

    public function guardarForos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iForoCatId' => ['required'],
            'iDocenteId' => ['required'],
            'cForoTitulo' => ['required', 'string', 'max:150'],
            'cForoDescripcion' => ['required', 'string'],
            'dtForoInicio' => ['required', 'date'],
            'dtForoFin' => ['required', 'date', 'after:dtForoInicio'],

            'iContenidoSemId' => ['required'],
            'iActTipoId' => ['required'],
            'idDocCursoId' => ['required'],
        ], [
            'iForoCatId.required' => 'No se encontró el identificador iForoCatId',
            'iDocenteId.required' => 'No se encontró el identificador iDocenteId',

            'cForoTitulo.required' => 'Debe ingresar el título del foro',
            'cForoTitulo.string' => 'El título debe ser una cadena de texto',
            'cForoTitulo.max' => 'El título no debe exceder los 150 caracteres',

            'cForoDescripcion.required' => 'Debe ingresar la descripción del foro',
            'cForoDescripcion.string' => 'La descripción debe ser una cadena de texto',

            'dtForoInicio.required' => 'Debe ingresar la fecha de inicio del foro',
            'dtForoInicio.date' => 'La fecha de inicio debe ser una fecha válida',

            'dtForoFin.required' => 'Debe ingresar la fecha de fin del foro',
            'dtForoFin.date' => 'La fecha de fin debe ser una fecha válida',
            'dtForoFin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio',

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
                'iForoCatId',
                'iDocenteId',
                'iInstrumentoId',
                'iContenidoSemId',
                'iActTipoId',
                'idDocCursoId',
                'iCredId'

            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iForoCatId                     ?? NULL,
                $request->iDocenteId                     ?? NULL,
                $request->cForoTitulo                    ?? NULL,
                $request->cForoDescripcion               ?? NULL,
                $request->dtForoInicio                   ?? NULL,
                $request->dtForoFin                      ?? NULL,
                $request->cForoUrl                       ?? NULL,
                $request->cTareaUrlnstrumentoEvaluacion  ?? NULL,
                $request->iInstrumentoId                 ?? NULL,
                $request->iContenidoSemId                ?? NULL,
                $request->iActTipoId                     ?? NULL,
                $request->idDocCursoId                   ?? NULL,

                $request->iCredId                        ?? NULL
            ];

            $data = DB::select(
                'EXEC aula.SP_INS_Foros 
                    @_iForoCatId=?, 
                    @_iDocenteId=?, 
                    @_cForoTitulo=?, 
                    @_cForoDescripcion=?, 
                    @_dtForoInicio=?, 
                    @_dtForoFin=?, 
                    @_cForoUrl=?, 
                    @_cTareaUrlnstrumentoEvaluacion=?, 
                    @_iInstrumentoId=?,
                    @_iContenidoSemId=?,
                    @_iActTipoId=?,
                    @_idDocCursoId=?,
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iForoId > 0) {
                $message = 'Se ha guardado exitosamente';
                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => []],
                    Response::HTTP_OK
                );
            } else {
                $message = 'No se ha podido guardar';
                return new JsonResponse(
                    ['validated' => false, 'message' => $message, 'data' => []],
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
}
