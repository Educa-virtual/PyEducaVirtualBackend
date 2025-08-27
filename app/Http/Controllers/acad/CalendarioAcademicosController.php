<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CalendarioAcademicosController extends Controller
{
    public function obtenerCalendarioAcademicosxiSedeIdxiYAcadId(Request $request, $iYAcadId, $iSedeId)
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
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $fieldsToDecode = [
            'iYAcadId',
            'iSedeId',
            'iCredId',
        ];

        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->iYAcadId              ??      NULL,
            $request->iSedeId               ??      NULL,
            $request->iCredId               ??      NULL
        ];

        try {
            $data = DB::select(
                'EXEC [acad].[Sp_SEL_calendarioAcademicosxiYAcadIdxiSedeId] 
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
                'message' => $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine(),
                'data' => [],
            ];
            $estado = Response::HTTP_INTERNAL_SERVER_ERROR;
            return new JsonResponse($response, $estado);
        }
    }

    public function guardarCalendarioAcademicos(Request $request)
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
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iYAcadId',
                'iSedeId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iYAcadId                    ??  NULL,
                $request->iSedeId                     ??  NULL,

                $request->dtCalAcadInicio             ??  NULL,
                $request->dtCalAcadFin                ??  NULL,
                $request->dtCalAcadMatriculaInicio    ??  NULL,
                $request->dtCalAcadMatriculaFin       ??  NULL,
                $request->dtCalAcadMatriculaResagados ??  NULL,
                $request->dtFaseInicioRegular         ??  NULL,
                $request->dtFaseFinRegular            ??  NULL,
                $request->dtFaseInicioRecuperacion    ??  NULL,
                $request->dtFaseFinRecuperacion       ??  NULL,
                $request->dtAperTurnoInicio           ??  NULL,
                $request->dtAperTurnoFin              ??  NULL,
                $request->jsonHorarios                ??  NULL,

                $request->iCredId                     ??  NULL
            ];

            $data = DB::select(
                'exec acad.SP_INS_calendarioAcademicos 
                    @_iYAcadId=?, 
                    @_iSedeId=?, 
                    @_dtCalAcadInicio=?, 
                    @_dtCalAcadFin=?, 
                    @_dtCalAcadMatriculaInicio=?, 
                    @_dtCalAcadMatriculaFin=?, 
                    @_dtCalAcadMatriculaResagados=?, 
                    @_dtFaseInicioRegular=?, 
                    @_dtFaseFinRegular=?, 
                    @_dtFaseInicioRecuperacion=?, 
                    @_dtFaseFinRecuperacion=?, 
                    @_dtAperTurnoInicio=?, 
                    @_dtAperTurnoFin=?,  
                    @_jsonHorarios=?,  
                    @_iCredId=?',
                $parametros
            );
            if ($data[0]->iCalAcadId > 0) {
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
