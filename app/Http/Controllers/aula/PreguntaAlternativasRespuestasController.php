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
                    CASE 
                        WHEN GETDATE() BETWEEN dtInicio AND dtFin THEN CAST(1 AS BIT)
                        ELSE CAST(0 AS BIT)
                    END AS bActivo
                FROM aula.cuestionarios 
                WHERE iCuestionarioId = ?
            ', [$request->iCuestionarioId]);
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
            
            if ($data[0]->iPrgAltRptaId > 0) {
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
