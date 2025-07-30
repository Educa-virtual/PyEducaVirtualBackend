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
            'bEsPorPeriodo' => ['required'],
            'iNumeroPeriodo' => ['required_if:bEsPorPeriodo,true'],
            'iEscalaCalifIdPeriodo' => ['required_if:bEsPorPeriodo,true'],
            'cDetMatrConclusionDescPeriodo' => ['required_if:bEsPorPeriodo,true'],
            'iCredId' => ['required'],
        ], [
            'iEscalaCalifIdPromedio.required' => 'No se encontró el identificador iEscalaCalifIdPromedio',
            'cDetMatConclusionDescPromedio.required' => 'No ingresó una conclusión descriptiva para el promedio',
            'iEstudianteId.required' => 'No se encontró el identificador iEstudianteId',
            'iMatrId.required' => 'No se encontró el identificador iMatrId',
            'iIeCursoId.required' => 'No se encontró el identificador iIeCursoId',
            'iSeccionId.required' => 'No se encontró el identificador iSeccionId',
            'idDocCursoId.required' => 'No se encontró el identificador idDocCursoId',
            'iEscalaCalifIdPeriodo.required_if' => 'La escala calificativa del periodo es obligatoria cuando se evalúa por periodo.',
            'cDetMatrConclusionDescPeriodo.required_if' => 'La conclusión descriptiva del periodo es obligatoria cuando se evalúa por periodo.',
            'iCredId.required' => 'No se encontró el identificador iCredId',
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
                'iEscalaCalifIdPeriodo',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iEscalaCalifIdPromedio ?? null,               // @_iEscalaCalifIdPromedio
                $request->iEstudianteId ?? null,                        // @_iEstudianteId
                $request->iMatrId ?? null,                              // @_iMatrId
                $request->iDetMatrId ?? null,                           // @_iDetMatrId
                $request->iIeCursoId ?? null,                           // @_iIeCursoId
                $request->iSeccionId ?? null,                           // @_iSeccionId
                $request->idDocCursoId ?? null,                         // @_idDocCursoId
                $request->cDetMatConclusionDescPromedio ?? null,        // @_cDetMatConclusionDescPromedio
                $request->bEsPorPeriodo ? 1 : 0,                        // @_bEsPorPeriodo
                $request->bEsPorPeriodo ? $request->iNumeroPeriodo : null,        // @_iNumeroPeriodo
                $request->bEsPorPeriodo ? $request->iEscalaCalifIdPeriodo : null,  // @_iEscalaCalifIdPeriodo
                $request->bEsPorPeriodo ? $request->cDetMatrConclusionDescPeriodo : null, // @_cDetMatrConclusionDescPeriodo
                $request->iCredId ?? null                               // @_iCredId
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
                    @_bEsPorPeriodo=?,
                    @_iNumeroPeriodo=?,
                    @_iEscalaCalifIdPeriodo = ?,
                    @_cDetMatrConclusionDescPeriodo = ?,
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
