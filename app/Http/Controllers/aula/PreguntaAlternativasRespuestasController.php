<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PreguntaAlternativasRespuestasController extends Controller
{
    public function listarPreguntasxiCuestionarioIdxiEstudianteId(Request $request, $iCuestionarioId, $iEstudianteId)
    {
        $request->merge(['iCuestionarioId' => $iCuestionarioId]);
        $request->merge(['iEstudianteId' => $iEstudianteId]);

        try {
            $fieldsToDecode = [
                'iCuestionarioId',
                'iEstudianteId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCuestionarioId    ??  NULL,
                $request->iEstudianteId      ??  NULL,
                $request->iCredId            ??  NULL
            ];

            $data = DB::select(
                'exec aula.SP_SEL_preguntaAlternativasRespuestasxiCuestionarioIdxiEstudianteId
                    @_iCuestionarioId=?,   
                    @_iEstudianteId=?,   
                    @_iCredId=?',
                $parametros
            );
            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);

            foreach ($data as $pregunta) {
                if (!empty($pregunta->jsonAlternativas)) {
                    // Decodificamos jsonAlternativas
                    $alternativas = json_decode($pregunta->jsonAlternativas, true);
                    //Encriptamos cada iPregAlterId
                    foreach ($alternativas as &$alternativa) {
                        if (isset($alternativa['iPregAlterId'])) {
                            $alternativa['iPregAlterId'] = VerifyHash::encodexId($alternativa['iPregAlterId']);
                        }
                        if (isset($alternativa['iPrgAltRptaId'])) {
                            $alternativa['iPrgAltRptaId'] = VerifyHash::encodexId($alternativa['iPrgAltRptaId']);
                        }
                    }
                    $pregunta->jsonAlternativas = json_encode($alternativas);
                }
            }

            $bCuestionarioActivo = DB::selectOne('
               SELECT 
                    ISNULL(
                        (
                            SELECT TOP 1 par.iEstado
                            FROM aula.pregunta_alternativas_respuestas par
                            INNER JOIN aula.pregunta_alternativas pa ON pa.iPregAlterId = par.iPregAlterId
                            INNER JOIN aula.preguntas p ON p.iPregId = pa.iPregId
                            WHERE p.iCuestionarioId = c.iCuestionarioId
                            AND par.iEstudianteId = ?
                            AND par.iEstado = 2
                            ORDER BY par.iEstado DESC
                        ),
                        CASE 
                            WHEN GETDATE() BETWEEN c.dtInicio AND c.dtFin THEN 1
                            ELSE 0
                        END
                    ) AS bActivo
                FROM aula.cuestionarios c
                WHERE c.iCuestionarioId = ?
            ', [
                $request->iEstudianteId,
                $request->iCuestionarioId
            ]);

            return new JsonResponse(
                ['validated' => true, 'message' => 'Se ha obtenido exitosamente ', 'data' => $data, 'bCuestionarioActivo' => $bCuestionarioActivo->bActivo ?? NULL],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function guardarPreguntasxiCuestionarioIdxiEstudianteId(Request $request, $iCuestionarioId, $iEstudianteId)
    {
        $request->merge(['iCuestionarioId' => $iCuestionarioId]);
        $request->merge(['iEstudianteId' => $iEstudianteId]);

        $validator = Validator::make($request->all(), [
            'iCuestionarioId' => ['required'],
            'iEstudianteId' => ['required'],
            'iPregAlterId' => ['required'],
        ], [
            'iCuestionarioId.required' => 'No se encontró el identificador iCuestionarioId',
            'iEstudianteId.required' => 'No se encontró el identificador iEstudianteId',
            'iPregAlterId.required' => 'No se encontró el identificador iPregAlterId'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iCuestionarioId',
                'iEstudianteId',
                'iPregAlterId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCuestionarioId               ??  NULL,
                $request->iEstudianteId                 ??  NULL,
                $request->iPregAlterId                  ??  NULL,
                $request->cRespuesta                    ??  NULL,
                $request->iCredId                       ??  NULL
            ];

            $data = DB::select(
                'exec aula.SP_INS_preguntaAlternativasRespuestas 
                    @_iCuestionarioId=?,   
                    @_iEstudianteId=?,   
                    @_iPregAlterId=?,   
                    @_cRespuesta=?,  
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iEstado === '2') {
                $message = 'El cuestionario ya está finalizado';
                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => [], 'estado' => false],
                    Response::HTTP_OK
                );
            }

            if ($data[0]->iPrgAltRptaId > 0) {
                $message = 'Se ha guardado exitosamente';
                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => [], 'estado' => true],
                    Response::HTTP_OK
                );
            }

            $message = 'No se ha podido guardar';
            return new JsonResponse(
                ['validated' => false, 'message' => $message, 'data' => []],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function finalizarPreguntaAlternativasRespuestas(Request $request, $iCuestionarioId, $iEstudianteId)
    {
        $request->merge(['iCuestionarioId' => $iCuestionarioId]);
        $request->merge(['iEstudianteId' => $iEstudianteId]);

        $validator = Validator::make($request->all(), [
            'iCuestionarioId' => ['required'],
            'iEstudianteId' => ['required'],
        ], [
            'iCuestionarioId.required' => 'No se encontró el identificador iCuestionarioId',
            'iEstudianteId.required' => 'No se encontró el identificador iEstudianteId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iCuestionarioId',
                'iEstudianteId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCuestionarioId               ??  NULL,
                $request->iEstudianteId                 ??  NULL,
                $request->iCredId                       ??  NULL
            ];

            $data = DB::select(
                'exec aula.SP_UPD_preguntaAlternativasRespuestasxiCuestionarioIdxiEstudianteIdxFinalizar 
                    @_iCuestionarioId=?,   
                    @_iEstudianteId=?,   
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iCuestionarioId > 0) {
                $message = 'Se ha finalizado exitosamente';
                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => []],
                    Response::HTTP_OK
                );
            } else {
                $message = 'No se ha podido finalizar';
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

    public function obtenerResultadosxiCuestionarioId(Request $request, $iCuestionarioId)
    {
        $request->merge(['iCuestionarioId' => $iCuestionarioId]);

        try {
            $fieldsToDecode = [
                'iCuestionarioId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCuestionarioId    ??  NULL
            ];

            $data = DB::select(
                'exec aula.SP_SEL_estadisticaRespuestasxiCuestionarioId
                    @_iCuestionarioId=?',
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
