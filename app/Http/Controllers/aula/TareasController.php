<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;

class TareasController extends ApiController
{
   
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
        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iTareaId              ?? NULL,
            $request->iProgActId            ?? NULL,
            $request->iDocenteId            ?? NULL,
            $request->cTareaTitulo          ?? NULL,
            $request->cTareaDescripcion     ?? NULL,
            $request->cTareaArchivoAdjunto  ?? NULL,
            $request->cTareaIndicaciones    ?? NULL,
            $request->bTareaEsEvaluado      ?? NULL,
            $request->bTareaEsRestringida   ?? NULL,
            $request->bTareaEsGrupal        ?? NULL,
            $request->dtTareaInicio         ?? NULL,
            $request->dtTareaFin            ?? NULL,
            $request->cTareaComentarioDocente   ?? NULL,
            $request->iEstado                   ?? NULL,
            $request->iSesionId                 ?? NULL,
            $request->dtCreado                  ?? NULL,
            $request->dtActualizado             ?? NULL,

            //$request->iCredId

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
        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iTareaId              ?? NULL,
            $request->iProgActId            ?? NULL,
            $request->iDocenteId            ?? NULL,
            $request->cTareaTitulo          ?? NULL,
            $request->cTareaDescripcion     ?? NULL,
            $request->cTareaArchivoAdjunto  ?? NULL,
            $request->cTareaIndicaciones    ?? NULL,
            $request->bTareaEsEvaluado      ?? NULL,
            $request->bTareaEsRestringida   ?? NULL,
            $request->bTareaEsGrupal        ?? NULL,
            $request->dtTareaInicio         ?? NULL,
            $request->dtTareaFin            ?? NULL,
            $request->cTareaComentarioDocente   ?? NULL,
            $request->iEstado                   ?? NULL,
            $request->iSesionId                 ?? NULL,
            $request->dtCreado                  ?? NULL,
            $request->dtActualizado             ?? NULL,

            //$request->iCredId

        ];
        //return $parametros;
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
        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

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
        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',
            $request->iTareaId              ?? NULL,
            $request->iProgActId            ?? NULL,
            $request->iDocenteId            ?? NULL,
            $request->cTareaTitulo          ?? NULL,
            $request->cTareaDescripcion     ?? NULL,
            $request->cTareaArchivoAdjunto  ?? NULL,
            $request->cTareaIndicaciones    ?? NULL,
            $request->bTareaEsEvaluado      ?? NULL,
            $request->bTareaEsRestringida   ?? NULL,
            $request->bTareaEsGrupal        ?? NULL,
            $request->dtTareaInicio         ?? NULL,
            $request->dtTareaFin            ?? NULL,
            $request->cTareaComentarioDocente   ?? NULL,
            $request->iEstado                   ?? NULL,
            $request->iSesionId                 ?? NULL,
            $request->dtCreado                  ?? NULL,
            $request->dtActualizado             ?? NULL,

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
        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);


        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iTareaId              ?? NULL,
            $request->iProgActId            ?? NULL,
            $request->iDocenteId            ?? NULL,
            $request->cTareaTitulo          ?? NULL,
            $request->cTareaDescripcion     ?? NULL,
            $request->cTareaArchivoAdjunto  ?? NULL,
            $request->cTareaIndicaciones    ?? NULL,
            $request->bTareaEsEvaluado      ?? NULL,
            $request->bTareaEsRestringida   ?? NULL,
            $request->bTareaEsGrupal        ?? NULL,
            $request->dtTareaInicio         ?? NULL,
            $request->dtTareaFin            ?? NULL,
            $request->cTareaComentarioDocente   ?? NULL,
            $request->iEstado                   ?? NULL,
            $request->iSesionId                 ?? NULL,
            $request->dtCreado                  ?? NULL,
            $request->dtActualizado             ?? NULL,

            //$request->iCredId

        ];
        //return $parametros;
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
        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);


        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iTareaId              ?? NULL,
            $request->iProgActId            ?? NULL,
            $request->iDocenteId            ?? NULL,
            $request->cTareaTitulo          ?? NULL,
            $request->cTareaDescripcion     ?? NULL,
            $request->cTareaArchivoAdjunto  ?? NULL,
            $request->cTareaIndicaciones    ?? NULL,
            $request->bTareaEsEvaluado      ?? NULL,
            $request->bTareaEsRestringida   ?? NULL,
            $request->bTareaEsGrupal        ?? NULL,
            $request->dtTareaInicio         ?? NULL,
            $request->dtTareaFin            ?? NULL,
            $request->cTareaComentarioDocente   ?? NULL,
            $request->iEstado                   ?? NULL,
            $request->iSesionId                 ?? NULL,
            $request->dtCreado                  ?? NULL,
            $request->dtActualizado             ?? NULL,

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
            'iEstudianteId'
        ];
        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

       
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
