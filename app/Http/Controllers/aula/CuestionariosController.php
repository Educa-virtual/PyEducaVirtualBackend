<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CuestionariosController extends Controller
{
    public function guardarCuestionario(Request $request)
    {
        // Validación de los parámetros de entrada
        $validator = Validator::make($request->all(), [
            'iProgActId' => ['required'],
            'iDocenteId' => ['required'],
            'cTitulo' => ['required', 'max:250'],
            'cSubtitulo' => ['nullable', 'max:250'],
            'cDescripcion' => ['required'],
            'dtInicio'     => ['required', 'date'],
            'dtFin'        => ['required', 'date', 'after:dtInicio'],
        ], [
            'iProgActId.required' => 'No se encontro el identificador iProgActId',
            'iDocenteId.required' => 'No se encontro el identificador iDocenteId',
            'cTitulo.required' => 'No se encontro el identificador cTitulo',
            'cTitulo.max' => 'El título no debe exceder los 250 caracteres.',
            'cSubtitulo.max' => 'El subtítulo no debe exceder los 250 caracteres.',
            'cDescripcion.required' => 'No se encontro el identificador cDescripcion',
            'dtInicio.required'     => 'La fecha y hora de inicio es obligatoria',
            'dtInicio.date'         => 'La fecha de inicio no es válida.',
            'dtFin.required'        => 'La fecha y hora de fin es obligatoria',
            'dtFin.date'            => 'La fecha de fin no es válida.',
            'dtFin.after'  => 'La fecha de fin debe ser posterior a la fecha de inicio.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iProgActId',
                'iDocenteId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iProgActId        ?? NULL,
                $request->iDocenteId        ?? NULL,
                $request->cTitulo           ?? NULL,
                $request->cSubtitulo        ?? NULL,
                $request->cDescripcion      ?? NULL,
                $request->dtInicio          ?? NULL,
                $request->dtFin             ?? NULL,
                $request->cArchivoAdjunto   ?? NULL,

                $request->iCredId           ?? NULL
            ];

            $data = DB::select(
                'exec aula.SP_INS_cuestionarios 
                    @_iProgActId=?,
                    @_iDocenteId=?,
                    @_cTitulo=?,
                    @_cSubtitulo=?,
                    @_cDescripcion=?,
                    @_dtInicio=?,
                    @_dtFin=?,
                    @_cArchivoAdjunto=?,
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iCuestionarioId > 0) {
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
        } catch (Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function actualizarCuestionario(Request $request, $iCuestionarioId)
    {    
        $request->merge(['iCuestionarioId' => $iCuestionarioId]);
        // Validación de los parámetros de entrada
        $validator = Validator::make($request->all(), [
            'iCuestionarioId' => ['required'],
            'iProgActId' => ['required'],
            'iDocenteId' => ['required'],
            'cTitulo' => ['required', 'max:250'],
            'cSubtitulo' => ['nullable', 'max:250'],
            'cDescripcion' => ['required'],
            'dtInicio'     => ['required', 'date'],
            'dtFin'        => ['required', 'date', 'after:dtInicio'],
        ], [
            'iCuestionarioId.required' => 'No se encontro el identificador iCuestionarioId',
            'iProgActId.required' => 'No se encontro el identificador iProgActId',
            'iDocenteId.required' => 'No se encontro el identificador iDocenteId',
            'cTitulo.required' => 'No se encontro el identificador cTitulo',
            'cTitulo.max' => 'El título no debe exceder los 250 caracteres.',
            'cSubtitulo.max' => 'El subtítulo no debe exceder los 250 caracteres.',
            'cDescripcion.required' => 'No se encontro el identificador cDescripcion',
            'dtInicio.required'     => 'La fecha y hora de inicio es obligatoria',
            'dtInicio.date'         => 'La fecha de inicio no es válida.',
            'dtFin.required'        => 'La fecha y hora de fin es obligatoria',
            'dtFin.date'            => 'La fecha de fin no es válida.',
            'dtFin.after'  => 'La fecha de fin debe ser posterior a la fecha de inicio.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
       try {
            $fieldsToDecode = [
                'iProgActId',
                'iDocenteId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCuestionarioId   ?? NULL,
                $request->iProgActId        ?? NULL,
                $request->iDocenteId        ?? NULL,
                $request->cTitulo           ?? NULL,
                $request->cSubtitulo        ?? NULL,
                $request->cDescripcion      ?? NULL,
                $request->dtInicio          ?? NULL,
                $request->dtFin             ?? NULL,
                $request->cArchivoAdjunto   ?? NULL,

                $request->iCredId           ?? NULL
            ];

            $data = DB::select(
                'exec aula.SP_UPD_cuestionarios 
                    @_iCuestionarioId=?,
                    @_iProgActId=?,
                    @_iDocenteId=?,
                    @_cTitulo=?,
                    @_cSubtitulo=?,
                    @_cDescripcion=?,
                    @_dtInicio=?,
                    @_dtFin=?,
                    @_cArchivoAdjunto=?,
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iCuestionarioId > 0) {
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
    public function eliminarCuestionario(Request $request,$iCuestionarioId)
    {
        $request->merge(['iCuestionarioId' => $iCuestionarioId]);
        // Validación de los parámetros de entrada
        $validator = Validator::make($request->all(), [
            'iCuestionarioId' => ['required'],
        ], [
            'iCuestionarioId.required' => 'No se encontro el identificador iCuestionarioId',
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
                'iCredId',
            ];
            
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCuestionarioId      ??  NULL,
                $request->iCredId              ??  NULL
            ];

            $data = DB::select(
                'exec aula.SP_DEL_cuestionariosxiCuestionarioId
                    @_iCuestionarioId=?, 
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iCuestionarioId > 0) {
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
}
