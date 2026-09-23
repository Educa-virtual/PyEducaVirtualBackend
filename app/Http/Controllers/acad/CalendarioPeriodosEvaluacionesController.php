<?php

namespace App\Http\Controllers\acad;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use App\Http\Requests\acad\ListarCalendarioPeriodosRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\acad\CalendarioPeriodosEvaluaciones;

class CalendarioPeriodosEvaluacionesController extends Controller
{
    public function listarCalendarioPeriodos(ListarCalendarioPeriodosRequest $request)
    {
        try {
            $data = CalendarioPeriodosEvaluaciones::selCalendariosPeriodosEvaluaciones($request);
            return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
        } catch(\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function procesarCalendarioPeriodos(Request $request)
    {
        try {
            $data = CalendarioPeriodosEvaluaciones::updCalendarioPeriodosAcademicos($request);
            return FormatearMensajeHelper::ok('Se proceso el calendario', $data);
        } catch(\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarCalendarioPeriodo(Request $request)
    {
        try {
            $data = CalendarioPeriodosEvaluaciones::updCalendarioPeriodoAcademico($request);
            return FormatearMensajeHelper::ok('Se actualizo el calendario', $data);
        } catch(\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function obtenerPeriodosxiYAcadIdxiSedeIdxFaseRegular(Request $request, $iYAcadId, $iSedeId)
    {
        $request->merge([
            'iYAcadId' => $iYAcadId,
            'iSedeId' => $iSedeId,
        ]);

        $validator = Validator::make($request->all(), [
            'iYAcadId' => ['required'],
            'iSedeId' => ['required'],
        ], [
            'iYAcadId.required' => 'No se encontró el identificador iYAcadId',
            'iSedeId.required' => 'No se encontró el identificador iSedeId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $fieldsToDecode = [
            'iYAcadId',
            'iSedeId',
            'iCredId',
            'iPeriodoEvalAperId',
        ];

        $request = VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->iYAcadId ?? null,
            $request->iSedeId ?? null,
            $request->iCredId ?? null,
        ];

        try {
            $data = DB::select(
                'EXEC [acad].[Sp_SEL_obtenerPeriodosxiYAcadIdxiSedeIdxFaseRegular] 
                    @_iYAcadId=?,
                    @_iSedeId=?,
                    @_iCredId=?',
                $parametros
            );
            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = Response::HTTP_OK;

            return $response;
        } catch (\Exception $e) {
            // Manejo de excepción y respuesta de error
            $response = [
                'validated' => false,
                'message' => $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine(),
                'data' => [],
            ];
            $estado = Response::HTTP_INTERNAL_SERVER_ERROR;

            return new JsonResponse($response, $estado);
        }
    }

    public function guardarCalendarioPeriodosEvalaciones(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iYAcadId' => ['required'],
            'iSedeId' => ['required'],
        ], [
            'iYAcadId.required' => 'No se encontró el identificador iYAcadId',
            'iSedeId.required' => 'No se encontró el identificador iSedeId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iYAcadId',
                'iSedeId',
                'iPeriodoEvalId',
                'iCredId',
            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iYAcadId ?? null,
                $request->iSedeId ?? null,
                $request->iPeriodoEvalId ?? null,
                $request->jsonPeriodos ?? null,

                $request->iCredId ?? null,
            ];

            $data = DB::select(
                'exec acad.SP_INS_calendarioPeriodosEvaluaciones 
                    @_iYAcadId=?, 
                    @_iSedeId=?, 
                    @_iPeriodoEvalId=?, 
                    @_jsonPeriodos=?, 
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iPeriodoEvalAperId > 0) {
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
}
