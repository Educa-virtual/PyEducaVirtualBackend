<?php

namespace App\Http\Controllers\aula;

use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TareasController extends Controller
{
    public function guardarTareas(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'iDocenteId' => ['required'],
            'cTareaTitulo' => ['required', 'string', 'max:250'],
            'cTareaDescripcion' => ['required', 'string'],
            'dtTareaInicio' => ['required'],
            'dtTareaFin' => ['required'],
            'iContenidoSemId' => ['required'],
            'iActTipoId' => ['required'],
            'iYAcadId' => ['required'],

        ], [
            'iDocenteId.required' => 'No se encontró el identificador de la persona',

            'cTareaTitulo.required' => 'Debe ingresar el título',
            'cTareaTitulo.string' => 'El título debe ser una cadena de texto',
            'cTareaTitulo.max' => 'El título no debe exceder los 150 caracteres',
            'cTareaDescripcion.required' => 'Debe ingresar la descripción',
            'cTareaDescripcion.string' => 'La descripción debe ser una cadena de texto',
            'dtTareaInicio.required' => 'Debe ingresar la fecha de inicio',
            'dtTareaFin.required' => 'Debe ingresar la fecha de fin',
            'iContenidoSemId.required' => 'No se encontró el identificador iContenidoSemId',
            'iActTipoId.required' => 'No se encontró el identificador iActTipoId',
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
                'iDocenteId',
                'iContenidoSemId',
                'iActTipoId',
                'idDocCursoId',
                'iCredId',
                'iCapacitacionId',
                'iYAcadId',

            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iDocenteId ?? null,
                $request->cTareaTitulo ?? null,
                $request->cTareaDescripcion ?? null,
                $request->cTareaArchivoAdjunto ?? null,
                $request->bTareaEsGrupal ?? null,
                $request->dtTareaInicio ?? null,
                $request->dtTareaFin ?? null,
                $request->iContenidoSemId ?? null,
                $request->iActTipoId ?? null,
                $request->idDocCursoId ?? null,
                $request->iCapacitacionId ?? null,
                $request->iYAcadId ?? null,

                $request->iCredId ?? null,
                $request->jCompetencias ?? null,
            ];

            $data = DB::select(
                'EXEC aula.SP_INS_tareas 
                    @_iDocenteId=?, 
                    @_cTareaTitulo=?, 
                    @_cTareaDescripcion=?, 
                    @_cTareaArchivoAdjunto=?,
                    @_bTareaEsGrupal=?,  
                    @_dtTareaInicio=?, 
                    @_dtTareaFin=?, 
                    @_iContenidoSemId=?,
                    @_iActTipoId=?,
                    @_idDocCursoId=?,
                    @_iCapacitacionId=?,
                    @_iYAcadId=?,
                    @_iCredId=?,
                    @_jCompetencias=?',
                $parametros
            );

            if ($data[0]->iTareaId > 0) {
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

    public function actualizarTareasxiTareaId(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'iTareaId' => ['required'],
            'cTareaTitulo' => ['required', 'string', 'max:250'],
            'cTareaDescripcion' => ['required', 'string'],
            'dtTareaInicio' => ['required'],
            'dtTareaFin' => ['required'],
        ], [
            'iTareaId.required' => 'No se encontró el identificador iTareaId',

            'cTareaTitulo.required' => 'Debe ingresar el título',
            'cTareaTitulo.string' => 'El título debe ser una cadena de texto',
            'cTareaTitulo.max' => 'El título no debe exceder los 150 caracteres',

            'cTareaDescripcion.required' => 'Debe ingresar la descripción',
            'cTareaDescripcion.string' => 'La descripción debe ser una cadena de texto',

            'dtTareaInicio.required' => 'Debe ingresar la fecha de inicio',

            'dtTareaFin.required' => 'Debe ingresar la fecha de fin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iTareaId',
                'iCredId',

            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iTareaId ?? null,
                $request->cTareaTitulo ?? null,
                $request->cTareaDescripcion ?? null,
                $request->cTareaArchivoAdjunto ?? null,
                $request->dtTareaInicio ?? null,
                $request->dtTareaFin ?? null,

                $request->iCredId ?? null,
                $request->jCompetencias ?? null,
            ];

            $data = DB::select(
                'EXEC aula.SP_UPD_tareasxiTareaId 
                    @_iTareaId=?, 
                    @_cTareaTitulo=?, 
                    @_cTareaDescripcion=?, 
                    @_cTareaArchivoAdjunto=?,
                    @_dtTareaInicio=?, 
                    @_dtTareaFin=?, 
                    @_iCredId=?,
                    @_jCompetencias=?',
                $parametros
            );

            if ($data[0]->iTareaId > 0) {
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

        $fieldsToDecode = [
            'iTareaId',
            'iProgActId',
            'iDocenteId',
        ];
        $request = VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iTareaId ?? null,
            $request->iProgActId ?? null,
            $request->iDocenteId ?? null,
            $request->cTareaTitulo ?? null,
            $request->cTareaDescripcion ?? null,
            $request->cTareaArchivoAdjunto ?? null,
            $request->cTareaIndicaciones ?? null,
            $request->bTareaEsEvaluado ?? null,
            $request->bTareaEsRestringida ?? null,
            $request->bTareaEsGrupal ?? null,
            $request->dtTareaInicio ?? null,
            $request->dtTareaFin ?? null,
            $request->cTareaComentarioDocente ?? null,
            $request->iEstado ?? null,
            $request->iSesionId ?? null,
            $request->dtCreado ?? null,
            $request->dtActualizado ?? null,

            // $request->iCredId

        ];

        try {
            $data = DB::select('exec aula.SP_SEL_tareas
            ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            // Codificar los id de los registros a enviar al frontend
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
            'iTareaId',
            'iProgActId',
            'iDocenteId',
        ];
        $request = VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iTareaId ?? null,
            $request->iProgActId ?? null,
            $request->iDocenteId ?? null,
            $request->cTareaTitulo ?? null,
            $request->cTareaDescripcion ?? null,
            $request->cTareaArchivoAdjunto ?? null,
            $request->cTareaIndicaciones ?? null,
            $request->bTareaEsEvaluado ?? null,
            $request->bTareaEsRestringida ?? null,
            $request->bTareaEsGrupal ?? null,
            $request->dtTareaInicio ?? null,
            $request->dtTareaFin ?? null,
            $request->cTareaComentarioDocente ?? null,
            $request->iEstado ?? null,
            $request->iSesionId ?? null,
            $request->dtCreado ?? null,
            $request->dtActualizado ?? null,

            // $request->iCredId

        ];
        // return $parametros;
        try {
            switch ($request->opcion) {
                case 'GUARDARxProgActxiTarea':
                    $data = DB::select('exec aula.SP_INS_tareas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    break;
                case 'ACTUALIZAR_TITULO_TAREA':
                case 'ACTUALIZARxProgActxiTarea':
                case 'ACTUALIZARxiTareaId':
                    $data = DB::select('exec aula.SP_UPD_tareas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    break;
                case 'ELIMINARxiTareaid':
                    $data = DB::select('exec aula.SP_DEL_tareas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    break;
            }

            if ($data[0]->iTareaId > 0) {

                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function getTareasxiCursoId(Request $request)
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
            'iCursoId',
            'iProgActId',
        ];
        $request = VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->iCursoId,
        ];

        try {
            $data = DB::select('exec aula.SP_SEL_obtenerTareas
                ?', $parametros);

            // Codificar los id de los registros a enviar al frontend
            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function update(Request $request)
    {

        $request->validate(
            ['opcion' => 'required'],
            ['opcion.required' => 'Hubo un problema al obtener la acción']
        );

        $fieldsToDecode = [
            'iTareaId',
            'iProgActId',
            'iDocenteId',
        ];
        $request = VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',
            $request->iTareaId ?? null,
            $request->iProgActId ?? null,
            $request->iDocenteId ?? null,
            $request->cTareaTitulo ?? null,
            $request->cTareaDescripcion ?? null,
            $request->cTareaArchivoAdjunto ?? null,
            $request->cTareaIndicaciones ?? null,
            $request->bTareaEsEvaluado ?? null,
            $request->bTareaEsRestringida ?? null,
            $request->bTareaEsGrupal ?? null,
            $request->dtTareaInicio ?? null,
            $request->dtTareaFin ?? null,
            $request->cTareaComentarioDocente ?? null,
            $request->iEstado ?? null,
            $request->iSesionId ?? null,
            $request->dtCreado ?? null,
            $request->dtActualizado ?? null,

        ];

        try {
            $data = DB::select('exec aula.SP_UPD_tareas
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            if ($data[0]->iTareaId > 0) {

                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function delete(Request $request)
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
            'iTareaId',
            'iProgActId',
            'iDocenteId',
        ];
        $request = VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iTareaId ?? null,
            $request->iProgActId ?? null,
            $request->iDocenteId ?? null,
            $request->cTareaTitulo ?? null,
            $request->cTareaDescripcion ?? null,
            $request->cTareaArchivoAdjunto ?? null,
            $request->cTareaIndicaciones ?? null,
            $request->bTareaEsEvaluado ?? null,
            $request->bTareaEsRestringida ?? null,
            $request->bTareaEsGrupal ?? null,
            $request->dtTareaInicio ?? null,
            $request->dtTareaFin ?? null,
            $request->cTareaComentarioDocente ?? null,
            $request->iEstado ?? null,
            $request->iSesionId ?? null,
            $request->dtCreado ?? null,
            $request->dtActualizado ?? null,

            // $request->iCredId

        ];
        // return $parametros;
        try {
            $data = DB::select('exec aula.SP_DEL_tareas
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            if ($data[0]->iTareaId > 0) {

                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function updatexiTareaId(Request $request)
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
            'iTareaId',
            'iProgActId',
            'iDocenteId',
        ];
        $request = VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iTareaId ?? null,
            $request->iProgActId ?? null,
            $request->iDocenteId ?? null,
            $request->cTareaTitulo ?? null,
            $request->cTareaDescripcion ?? null,
            $request->cTareaArchivoAdjunto ?? null,
            $request->cTareaIndicaciones ?? null,
            $request->bTareaEsEvaluado ?? null,
            $request->bTareaEsRestringida ?? null,
            $request->bTareaEsGrupal ?? null,
            $request->dtTareaInicio ?? null,
            $request->dtTareaFin ?? null,
            $request->cTareaComentarioDocente ?? null,
            $request->iEstado ?? null,
            $request->iSesionId ?? null,
            $request->dtCreado ?? null,
            $request->dtActualizado ?? null,

        ];

        try {
            $data = DB::select('exec aula.SP_UPD_tareas
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            if ($data[0]->iTareaId > 0) {

                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function crearActualizarGrupo(Request $request) {}

    public function obtenerTareaxiTareaidxiEstudianteId(Request $request)
    {
        $fieldsToDecode = [
            'iTareaId',
            'iEstudianteId',
        ];
        $request = VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->iTareaId,
            $request->iEstudianteId,
        ];

        try {
            $data = DB::select('exec aula.SP_SEL_tareasxiTareaIdxiEstudianteId
                ?,?', $parametros);

            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }
}
