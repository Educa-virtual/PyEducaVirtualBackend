<?php

namespace App\Http\Controllers\acad;

use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ContenidoSemanasController extends Controller
{
    public function guardarContenidoSemanas(Request $request)
    {   // tiposuario: DOCENTE - INSTRUCTOR
        // Reglas comunes
        $commonRules = [
            'iYAcadId' => ['required'],
            'cContenidoSemTitulo' => ['required', 'string', 'max:250'],
            'cTipoUsuario' => ['required'],
        ];

        // Mensajes comunes
        $commonMessages = [
            'iYAcadId.required' => 'No se encontró el identificador iYAcadId',
            'cContenidoSemTitulo.required' => 'Debe ingresar el título',
            'cContenidoSemTitulo.string' => 'El título debe ser una cadena de texto',
            'cContenidoSemTitulo.max' => 'El título no debe exceder los 250 caracteres',
            'cTipoUsuario.required' => 'No se detectó el tipo de usuario',
        ];

        // Reglas adicionales por tipo de usuario
        $extraRules = [
            'DOCENTE' => [
                'iPeriodoEvalAperId' => ['required'],
                'iTipExp' => ['required'],
                'cAdjunto' => ['required'],
                'idDocCursoId' => ['required'],
            ],
            'INSTRUCTOR' => ['iCapacitacionId' => ['required']], // Sin reglas adicionales
        ];

        // Mensajes adicionales por tipo de usuario
        $extraMessages = [
            'DOCENTE' => [
                'iPeriodoEvalAperId.required' => 'No se encontró el identificador iPeriodoEvalAperId',
                'iTipExp.required' => 'No se encontró el identificador iTipExp',
                'cAdjunto.required' => 'No se encontró el documento adjunto',
                'idDocCursoId.required' => 'No se encontró el identificador idDocCursoId',
            ],
            'INSTRUCTOR' => ['iCapacitacionId.required' => 'No se encontró el identificador iCapacitacionId'],
        ];

        // Selección dinámica de reglas y mensajes
        $tipo = $request->cTipoUsuario;
        $rules = array_merge($commonRules, $extraRules[$tipo] ?? []);
        $messages = array_merge($commonMessages, $extraMessages[$tipo] ?? []);

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iYAcadId',
                'idDocCursoId',
                'iPeriodoEvalAperId',
                'iTipExp',
                'iCredId',
                'iCapacitacionId',

            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->cTipoUsuario ?? null,
                $request->iYAcadId ?? null,
                $request->idDocCursoId ?? null,
                $request->iCapacitacionId ?? null,
                $request->cContenidoSemTitulo ?? null,
                $request->iPeriodoEvalAperId ?? null,
                $request->iTipExp ?? null,
                $request->cAdjunto ?? null,
                $request->iCredId ?? null,
            ];

            $data = DB::select(
                'EXEC acad.Sp_INS_contenidoSemanasxSesionAprendizaje 
                    @_cTipoUsuario=?, 
                    @_iYAcadId=?, 
                    @_idDocCursoId=?, 
                    @_iCapacitacionId=?, 
                    @_cContenidoSemTitulo=?, 
                    @_iPeriodoEvalAperId=?, 
                    @_iTipExp=?, 
                    @_cAdjunto=?, 
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iContenidoSemId > 0) {
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

    public function actualizarContenidoSemanas(Request $request, $iContenidoSemId)
    {
        $request->merge([
            'iContenidoSemId' => $iContenidoSemId,
        ]);

        // Reglas comunes
        $commonRules = [
            'iContenidoSemId' => ['required'],
            'cContenidoSemTitulo' => ['required', 'string', 'max:250'],
            'cTipoUsuario' => ['required'],
        ];

        // Mensajes comunes
        $commonMessages = [
            'iContenidoSemId.required' => 'No se encontró el identificador de la semana',
            'cContenidoSemTitulo.required' => 'Debe ingresar el título',
            'cContenidoSemTitulo.string' => 'El título debe ser una cadena de texto',
            'cContenidoSemTitulo.max' => 'El título no debe exceder los 250 caracteres',
            'cTipoUsuario.required' => 'No se detectó el tipo de usuario',
        ];

        // Reglas adicionales por tipo de usuario
        $extraRules = [
            'DOCENTE' => [
                'iPeriodoEvalAperId' => ['required'],
                'iTipExp' => ['required'],
                'cAdjunto' => ['required'],
            ],
            'INSTRUCTOR' => [], // Sin reglas adicionales
        ];

        // Mensajes adicionales por tipo de usuario
        $extraMessages = [
            'DOCENTE' => [
                'iPeriodoEvalAperId.required' => 'No se encontró el identificador iPeriodoEvalAperId',
                'iTipExp.required' => 'No se encontró el identificador iTipExp',
                'cAdjunto.required' => 'No se encontró el documento adjunto',
            ],
            'INSTRUCTOR' => [],
        ];

        // Selección dinámica de reglas y mensajes
        $tipo = $request->cTipoUsuario;
        $rules = array_merge($commonRules, $extraRules[$tipo] ?? []);
        $messages = array_merge($commonMessages, $extraMessages[$tipo] ?? []);

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iContenidoSemId',
                'iPeriodoEvalAperId',
                'iTipExp',
                'iCredId',

            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->cTipoUsuario ?? null,
                $request->iContenidoSemId ?? null,
                $request->cContenidoSemTitulo ?? null,
                $request->iPeriodoEvalAperId ?? null,
                $request->iTipExp ?? null,
                $request->cAdjunto ?? null,
                $request->iCredId ?? null,
            ];

            $data = DB::select(
                'EXEC acad.Sp_UPD_contenidoSemanasxSesionAprendizajexiContenidoSemId
                    @_cTipoUsuario=?, 
                    @_iContenidoSemId=?, 
                    @_cContenidoSemTitulo=?, 
                    @_iPeriodoEvalAperId=?, 
                    @_iTipExp=?, 
                    @_cAdjunto=?, 
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iContenidoSemId > 0) {
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

    public function eliminarContenidoSemanas(Request $request, $iContenidoSemId)
    {
        $request->merge([
            'iContenidoSemId' => $iContenidoSemId,
        ]);

        $validator = Validator::make($request->all(), [
            'iContenidoSemId' => ['required'],
        ], [
            'iContenidoSemId.required' => 'No se encontró el identificador iContenidoSemId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iContenidoSemId',
                'iCredId',

            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iContenidoSemId ?? null,
                $request->iCredId ?? null,
            ];

            $data = DB::select(
                'EXEC acad.Sp_DEL_contenidoSemanasxSesionAprendizajexiContenidoSemId
                    @_iContenidoSemId=?, 
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iContenidoSemId > 0) {
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

    public function obtenerContenidoSemanasxiContenidoSemId(Request $request, $iContenidoSemId)
    {
        $request->merge([
            'iContenidoSemId' => $iContenidoSemId,
        ]);

        $validator = Validator::make($request->all(), [
            'iContenidoSemId' => ['required'],
        ], [
            'iContenidoSemId.required' => 'No se encontró el identificador iContenidoSemId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iContenidoSemId',
                'iPeriodoEvalAperId',
                'iCredId',

            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iContenidoSemId ?? null,
                $request->iCredId ?? null,
            ];

            $data = DB::select(
                'EXEC acad.Sp_SEL_contenidoSemanasxSesionAprendizajexiContenidoSemId
                    @_iContenidoSemId=?, 
                    @_iCredId=?',
                $parametros
            );

            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);

            return new JsonResponse(
                ['validated' => true, 'message' => 'Se obtuvo la información exitosamente', 'data' => $data],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function obtenerContenidoSemanasxidDocCursoIdxiYAcadId(Request $request, $idDocCursoId, $iYAcadId)
    {
        $request->merge([
            'idDocCursoId' => $idDocCursoId,
            'iYAcadId' => $iYAcadId,
        ]);

        $validator = Validator::make($request->all(), [
            'idDocCursoId' => ['required'],
            'iYAcadId' => ['required'],
        ], [
            'idDocCursoId.required' => 'No se encontró el identificador del curso docente',
            'iYAcadId.required' => 'No se encontró el identificador del año académico',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'idDocCursoId',
                'iYAcadId',
                'iContenidoSemId',
                'iPeriodoEvalAperId',
                'iCredId',

            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->idDocCursoId ?? null,
                $request->iYAcadId ?? null,
                $request->iCredId ?? null,
            ];

            $data = DB::select(
                'EXEC acad.Sp_SEL_contenidoSemanasxSesionAprendizajexidDocCursoIdxiYAcadId
                    @_idDocCursoId=?, 
                    @_iYAcadId=?, 
                    @_iCredId=?',
                $parametros
            );

            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);

            return new JsonResponse(
                ['validated' => true, 'message' => 'Se obtuvo la información exitosamente', 'data' => $data],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function obtenerContenidoSemanasxiCapacitacionIdxiYAcadId(Request $request, $iCapacitacionId, $iYAcadId)
    {
        $request->merge([
            'iCapacitacionId' => $iCapacitacionId,
            'iYAcadId' => $iYAcadId,
        ]);

        $validator = Validator::make($request->all(), [
            'iCapacitacionId' => ['required'],
            'iYAcadId' => ['required'],
        ], [
            'iCapacitacionId.required' => 'No se encontró el identificador de la capacitación',
            'iYAcadId.required' => 'No se encontró el identificador del año académico',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iCapacitacionId',
                'iYAcadId',
                'iContenidoSemId',
                'iCredId',

            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCapacitacionId ?? null,
                $request->iYAcadId ?? null,
                $request->iCredId ?? null,
            ];

            $data = DB::select(
                'EXEC acad.Sp_SEL_contenidoSemanasxSesionAprendizajexiCapacitacionIdxiYAcadId
                    @_iCapacitacionId=?, 
                    @_iYAcadId=?, 
                    @_iCredId=?',
                $parametros
            );

            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);

            return new JsonResponse(
                ['validated' => true, 'message' => 'Se obtuvo la información exitosamente', 'data' => $data],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function obtenerActividadesxiContenidoSemId(Request $request, $iContenidoSemId)
    {
        $request->merge([
            'iContenidoSemId' => $iContenidoSemId,
        ]);

        $validator = Validator::make($request->all(), [
            'iContenidoSemId' => ['required'],
        ], [
            'iContenidoSemId.required' => 'No se encontró el identificador del contenido semana',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iContenidoSemId',
                'idDocCursoId',
                'iCapacitacionId',
                'iCredId',

            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iContenidoSemId ?? null,
                $request->idDocCursoId ?? null,
                $request->iCapacitacionId ?? null,
                $request->cPerfil ?? null,
                $request->iCredId ?? null,
            ];

            $data = DB::select(
                'EXEC acad.Sp_SEL_contenidoSemanasxActividadesxiContenidoSemId
                    @_iContenidoSemId=?, 
                    @_idDocCursoId=?, 
                    @_iCapacitacionId=?, 
                    @_cPerfil=?, 
                    @_iCredId=?',
                $parametros
            );
            foreach ($data as $item) {
                $item->iEstado = (int) $item->iEstado;
                $item->iActTipoId = (int) $item->iActTipoId;
                $item->iEstadoActividad = (int) $item->iEstadoActividad;
            }

            return new JsonResponse(
                ['validated' => true, 'message' => 'Se obtuvo la información exitosamente', 'data' => $data],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function list(Request $request)
    {
        $request->validate(
            [
                'opcion' => 'required',
            ],
            [
                'opcion.required' => 'Hubo un problema al obtener la acción',
            ]
        );

        $fieldsToDecodeByOption = [
            'default' => ['iContenidoSemId', 'iIndActId'],
            'CONSULTARxiSilaboId' => ['iContenidoSemId', 'iIndActId', 'valorBusqueda'],
        ];

        $fieldsToDecode = $fieldsToDecodeByOption[$request->opcion] ?? $fieldsToDecodeByOption['default'];

        $request = VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iContenidoSemId ?? null,
            $iIndActId ?? null,
            $request->cContenidoSemTitulo ?? null,
            $request->cContenidoSemNumero ?? null,
            $request->cContenidoSemDescripcion ?? null,

            $request->iCredId,

        ];

        try {
            $data = DB::select('exec acad.Sp_SEL_contenidoSemanas
                ?,?,?,?,?,?,?,?', $parametros);

            $fieldsToDecode = [
                'iContenidoSemId',
                'iIndActId',
            ];

            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'opcion' => 'required',
            ],
            [
                'opcion.required' => 'Hubo un problema al obtener la acción',
            ]
        );

        $fieldsToDecode = [
            'iContenidoSemId',
            'iIndActId',
        ];

        $request = VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',
            $iContenidoSemId ?? null,
            $iIndActId ?? null,
            $request->cContenidoSemTitulo ?? null,
            $request->cContenidoSemNumero ?? null,
            $request->cContenidoSemDescripcion ?? null,
            $request->iCredId,
        ];

        try {
            switch ($request->opcion) {
                case 'GUARDARxiIndActId':
                    $data = DB::select('exec acad.Sp_INS_contenidoSemanas
                    ?,?,?,?,?,?,?,?', $parametros);
                    break;
                case 'ACTUALIZARxiContenidoSemId':
                    $data = DB::select('exec acad.Sp_UPD_contenidoSemanas
                    ?,?,?,?,?,?,?,?', $parametros);
                    break;
                case 'ELIMINARxiContenidoSemId':
                    $parametros = [
                        $iContenidoSemId ?? null,
                        $request->iCredId,
                    ];
                    $data = DB::select('exec acad.Sp_DEL_contenidoSemanas ?,?', $parametros);
                    break;
            }
            if ($data[0]->iContenidoSemId > 0) {

                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }
}
