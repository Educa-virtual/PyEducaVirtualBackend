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
            'iDocenteId' => ['required'],
            'cTitulo' => ['required', 'max:250'],
            'cSubtitulo' => ['nullable', 'max:250'],
            'cDescripcion' => ['required'],
            'dtInicio'     => ['required'],
            'dtFin'        => ['required'],

            'iContenidoSemId' => ['required'],
            'iActTipoId' => ['required'],
            'iYAcadId' => ['required'],
        ], [
            'iDocenteId.required' => 'No se encontró el identificador iDocenteId',
            'cTitulo.required' => 'No se encontró el título',
            'cTitulo.max' => 'El título no debe exceder los 250 caracteres.',
            'cSubtitulo.max' => 'El subtítulo no debe exceder los 250 caracteres.',
            'cDescripcion.required' => 'No se encontró el identificador cDescripcion',
            'dtInicio.required'     => 'La fecha y hora de inicio es obligatoria',
            'dtFin.required'        => 'La fecha y hora de fin es obligatoria',

            'iContenidoSemId.required' => 'No se encontró el identificador iContenidoSemId',
            'iActTipoId.required' => 'No se encontró el identificador iActTipoId',
            'iYAcadId.required' => 'No se encontró el identificador del año académico',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iDocenteId',
                'iContenidoSemId',
                'iActTipoId',
                'idDocCursoId',
                'iCredId',
                'iCapacitacionId',
                'iYAcadId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iDocenteId        ?? NULL,
                $request->cTitulo           ?? NULL,
                $request->cSubtitulo        ?? NULL,
                $request->cDescripcion      ?? NULL,
                $request->dtInicio          ?? NULL,
                $request->dtFin             ?? NULL,
                $request->cArchivoAdjunto   ?? NULL,
                $request->iContenidoSemId           ?? NULL,
                $request->iActTipoId                ?? NULL,
                $request->idDocCursoId              ?? NULL,
                $request->iCapacitacionId           ?? NULL,
                $request->iYAcadId                  ?? NULL,

                $request->iCredId           ?? NULL
            ];

            $data = DB::select(
                'exec aula.SP_INS_cuestionarios 
                    @_iDocenteId=?,
                    @_cTitulo=?,
                    @_cSubtitulo=?,
                    @_cDescripcion=?,
                    @_dtInicio=?,
                    @_dtFin=?,
                    @_cArchivoAdjunto=?,
                    @_iContenidoSemId=?,
                    @_iActTipoId=?,
                    @_idDocCursoId=?,
                    @_iCapacitacionId=?,
                    @_iYAcadId=?,
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
        // return $request -> all();
        $request->merge(['iCuestionarioId' => $iCuestionarioId]);
        // Validación de los parámetros de entrada
        $validator = Validator::make($request->all(), [
            'iCuestionarioId' => ['required'],
            'iDocenteId' => ['required'],
            'cTitulo' => ['required', 'max:250'],
            'cSubtitulo' => ['nullable', 'max:250'],
            'cDescripcion' => ['required'],
            'dtInicio'     => ['required'],
            'dtFin'        => ['required'],
        ], [
            'iCuestionarioId.required' => 'No se encontró el identificador iCuestionarioId',
            'iDocenteId.required' => 'No se encontró el identificador iDocenteId',
            'cTitulo.required' => 'No se encontró el identificador cTitulo',
            'cTitulo.max' => 'El título no debe exceder los 250 caracteres.',
            'cSubtitulo.max' => 'El subtítulo no debe exceder los 250 caracteres.',
            'cDescripcion.required' => 'No se encontró el identificador cDescripcion',
            'dtInicio.required'     => 'La fecha y hora de inicio es obligatoria',
            'dtFin.required'        => 'La fecha y hora de fin es obligatoria',
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
                $request->iCuestionarioId   ?? NULL,
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
    public function eliminarCuestionario(Request $request, $iCuestionarioId)
    {
        $request->merge(['iCuestionarioId' => $iCuestionarioId]);
        // Validación de los parámetros de entrada
        $validator = Validator::make($request->all(), [
            'iCuestionarioId' => ['required'],
        ], [
            'iCuestionarioId.required' => 'No se encontró el identificador iCuestionarioId',
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


    public function obtenerCuestionarioxiCuestionarioId(Request $request, $iCuestionarioId)
    {
        $request->merge(['iCuestionarioId' => $iCuestionarioId]);
        // Validación de los parámetros de entrada
        $validator = Validator::make($request->all(), [
            'iCuestionarioId' => ['required'],
        ], [
            'iCuestionarioId.required' => 'No se encontró el identificador iCuestionarioId',
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
                'exec aula.SP_SEL_cuestionariosxiCuestionarioId
                    @_iCuestionarioId=?, 
                    @_iCredId=?',
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
