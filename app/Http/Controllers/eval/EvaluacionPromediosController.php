<?php

namespace App\Http\Controllers\eval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EvaluacionPromediosController extends Controller
{
    public function guardarConclusionxiEvaluacionIdxiEstudianteId(Request $request){
        
        // Validación de los parámetros de entrada
        $validator = Validator::make($request->all(), [
            'iEvaluacionId' => ['required'],
            'iEstudianteId' => ['required'],
            'cConclusionDescriptiva' => ['required'],
        ], [
            'iEvaluacionId.required' => 'No se encontró el identificador iEvaluacionId',
            'iEstudianteId.required' => 'No se encontró el identificador iEstudianteId',
            'cConclusionDescriptiva.required' => 'No se encontró la conclusión descriptiva',
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
                'iEstudianteId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iEvaluacionId                ?? NULL,
                $request->iEstudianteId                ?? NULL,
                $request->cConclusionDescriptiva       ?? NULL,
                $request->iCredId                      ?? NULL
            ];

            $data = DB::select(
                'exec eval.SP_INS_evaluacionPromediosxiEvaluacionIdxiEstudianteId 
                    @_iEvaluacionId=?,
                    @_iEstudianteId=?,
                    @_cConclusionDescriptiva=?,
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iEvalPromId > 0) {
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
