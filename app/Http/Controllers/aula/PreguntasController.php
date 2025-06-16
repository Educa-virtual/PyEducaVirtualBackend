<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;
use App\Http\Controllers\grl\PersonasController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PreguntasController extends Controller
{
    public function listarPreguntasxiCuestionarioId(Request $request, $iCuestionarioId)
    {
        $request->merge(['iCuestionarioId' => $iCuestionarioId]);

        try {
            $fieldsToDecode = [
                'iCuestionarioId',
                'iTipoPregId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCuestionarioId    ??  NULL,
                $request->iCredId                   ??  NULL
            ];

            $data = DB::select(
                'exec aula.SP_SEL_preguntasxiCuestionarioId 
                    @_iCuestionarioId=?,   
                    @_iCredId=?',
                $parametros
            );
            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);

            foreach ($data as &$pregunta) {
                if (!empty($pregunta->jsonAlternativas)) {
                    // Decodificamos jsonAlternativas
                    $alternativas = json_decode($pregunta->jsonAlternativas, true);
                    //Encriptamos cada iPregAlterId
                    foreach ($alternativas as &$alternativa) {
                        if (isset($alternativa['iPregAlterId'])) {
                            $alternativa['iPregAlterId'] = VerifyHash::encodexId($alternativa['iPregAlterId']);
                        }
                    }
                    $pregunta->jsonAlternativas = json_encode($alternativas);
                }
            }

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

    public function guardarPreguntas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iCuestionarioId' => ['required'],
            'iTipoPregId' => ['required'],
            'cPregunta' => ['required', 'string'],
        ], [
            'iCuestionarioId.required' => 'No se encontró el identificador iCuestionarioId',
            'iTipoPregId.required' => 'No se encontró el identificador iTipoPregId',
            'cPregunta.required' => 'Debe ingresar el enunciado de la pregunta',
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
                'iTipoPregId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCuestionarioId             ??  NULL,
                $request->iTipoPregId                 ??  NULL,
                $request->cPregunta                   ??  NULL,
                $request->cPreguntaImg                ??  NULL,
                $request->cIndicaciones               ??  NULL,
                $request->cTextoAyuda                 ??  NULL,
                $request->tTiempo                     ??  NULL,
                $request->jsonAlternativas            ??  NULL,
                $request->iCredId                     ??  NULL
            ];

            $data = DB::select(
                'exec aula.SP_INS_preguntas 
                    @_iCuestionarioId=?,   
                    @_iTipoPregId=?,   
                    @_cPregunta=?,   
                    @_cPreguntaImg=?,   
                    @_cIndicaciones=?,   
                    @_cTextoAyuda=?,   
                    @_tTiempo=?,   
                    @_jsonAlternativas=?,   
                    @_iCredId=?',
                $parametros
            );
            return $data;
            if ($data[0]->iPregId > 0) {
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

    public function actualizarPreguntasxiPregId(Request $request, $iPregId)
    {
        $request->merge(['iPregId' => $iPregId]);
        $validator = Validator::make($request->all(), [
            'iPregId' => ['required'],
            'iTipoPregId' => ['required'],
            'cPregunta' => ['required', 'string'],
        ], [
            'iPregId.required' => 'No se encontró el identificador iPregId',
            'iTipoPregId.required' => 'No se encontró el identificador iTipoPregId',
            'cPregunta.required' => 'Debe ingresar el enunciado de la pregunta',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iPregId',
                'iTipoPregId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iPregId             ??  NULL,
                $request->iTipoPregId                 ??  NULL,
                $request->cPregunta                   ??  NULL,
                $request->cPreguntaImg                ??  NULL,
                $request->cIndicaciones               ??  NULL,
                $request->cTextoAyuda                 ??  NULL,
                $request->tTiempo                     ??  NULL,
                $request->jsonAlternativas            ??  NULL,
                $request->iCredId                     ??  NULL
            ];

            $data = DB::select(
                'exec aula.SP_UPD_preguntasxiPregId
                    @_iPregId=?,   
                    @_iTipoPregId=?,   
                    @_cPregunta=?,   
                    @_cPreguntaImg=?,   
                    @_cIndicaciones=?,   
                    @_cTextoAyuda=?,   
                    @_tTiempo=?,   
                    @_jsonAlternativas=?,   
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iPregId > 0) {
                $message = 'Se ha actualizado exitosamente';
                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => []],
                    Response::HTTP_OK
                );
            } else {
                $message = 'No se ha podido actualizar';
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

    public function eliminarPreguntaxiPregId(Request $request, $iPregId)
    {
        $request->merge(['iPregId' => $iPregId]);

        $validator = Validator::make($request->all(), [
            'iPregId' => ['required'],
        ], [
            'iPregId.required' => 'No se encontró el identificador iPregId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iPregId',
                'iCredId'
            ];

            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iPregId      ??  NULL,
                $request->iCredId      ??  NULL
            ];
            $data = DB::select(
                'exec aula.SP_DEL_preguntasxiPregId
                    @_iPregId=?,    
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iPregId > 0) {
                $message = 'Se ha eliminado exitosamente';
                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => []],
                    Response::HTTP_OK
                );
            } else {
                $message = 'No se ha podido eliminar';
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
