<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class DetalleMatriculasController extends Controller
{
    public function guardarConclusionDescriptiva(Request $request, $iDetMatrId)
    {
        $request->merge(['iDetMatrId' => $iDetMatrId]);

         $validator = Validator::make($request->all(), [
            'iEscalaCalifIdPromedio' => ['required'],
            'cDetMatConclusionDescPromedio' => ['required'],
            'iEstudianteId' => ['required'],
            'iMatrId' => ['required'],
            'iIeCursoId' => ['required'],
            'iSeccionId' => ['required'],
            'idDocCursoId' => ['required'],
            'iCredId' => ['required'],
        ], [
            'iEscalaCalifIdPromedio.required' => 'No se encontro el identificador iEscalaCalifIdPromedio',
            'cDetMatConclusionDescPromedio.required' => 'No ingresó una conclusión descriptiva para el promedio',
            'iEstudianteId.required' => 'No se encontro el identificador iEstudianteId',
            'iMatrId.required' => 'No se encontro el identificador iMatrId',
            'iIeCursoId.required' => 'No se encontro el identificador iIeCursoId',
            'iSeccionId.required' => 'No se encontro el identificador iSeccionId',
            'idDocCursoId.required' => 'No se encontro el identificador idDocCursoId',
            'iCredId.required' => 'No se encontro el identificador iCredId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iEscalaCalifIdPromedio',
                'iEstudianteId',
                'iMatrId',
                'iIeCursoId',
                'iDetMatrId',
                'iSeccionId',
                'idDocCursoId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);
            
            $parametros = [
                $request->iEscalaCalifIdPromedio        ?? NULL,
                $request->iEstudianteId                 ?? NULL,
                $request->iMatrId                       ?? NULL,
                $request->iDetMatrId                    ?? NULL,
                $request->iIeCursoId                    ?? NULL,
                $request->iSeccionId                    ?? NULL,
                $request->idDocCursoId                   ?? NULL,
                $request->cDetMatConclusionDescPromedio ?? NULL,
                $request->iCredId                       ?? NULL
            ];
            $data = DB::select(
                'exec aula.SP_UPD_detalleMatriculasConclusionDescriptiva
                    @_iEscalaCalifIdPromedio=?,
                    @_iEstudianteId=?,
                    @_iMatrId=?,
                    @_iDetMatrId=?,
                    @_iIeCursoId=?,
                    @_iSeccionId=?,
                    @_idDocCursoId=?,
                    @_cDetMatConclusionDescPromedio=?,
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iDetMatrId > 0) {
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
        } catch (Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
