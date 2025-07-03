<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Support\Facades\Validator;

class TareaEstudiantesController extends Controller
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
            'iIeCursoId',
            'iYAcadId',
            'iSedeId',
            'iSeccionId',
            'iNivelGradoId',
            'iEscalaCalifId'
        ];
        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->opcion,
           
            $request->iTareaId                    ??      NULL,
            $request->iIeCursoId                  ??      NULL,
            $request->iYAcadId                    ??      NULL,
            $request->iSedeId                     ??      NULL,
            $request->iSeccionId                  ??      NULL,
            $request->iNivelGradoId               ??      NULL,

        ];
        try {
            $data = DB::select('exec aula.SP_SEL_tareaEstudiantes
                ?,?,?,?,?,?,?', $parametros);

            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function guardarCalificacionDocente(Request $request)
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
            'iTareaEstudianteId',
            'iTareaId',
            'iEstudianteId',
            'iEscalaCalifId',
            'iTareaCabGrupoId',
        ];
        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);


        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iTareaEstudianteId                    ??      NULL,
            $request->iTareaId                              ??      NULL,
            $request->iEstudianteId                         ??      NULL,
            $request->iEscalaCalifId                        ??      NULL,
            $request->nTareaEstudianteNota                  ??      NULL,
            $request->cTareaEstudianteComentarioDocente     ??      NULL,
            $request->cTareaEstudianteUrlEstudiante         ??      NULL,
            $request->iEstado                               ??      NULL,
            $request->iSesionId                             ??      NULL,
            $request->dtCreado                              ??      NULL,
            $request->dtActualizado                         ??      NULL,
            $request->iTareaCabGrupoId                      ??      NULL

        ];

        try {
            $data = DB::select('exec aula.SP_INS_tareaEstudiantes
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            if ($data[0]->iTareaEstudianteId > 0) {

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
    public function entregarEstudianteTarea(Request $request)
    {
        $fieldsToDecode = [
            'iTareaId',
            'iEstudianteId',
        ];

        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->iTareaId,
            $request->iEstudianteId,
            $request->cTareaEstudianteUrlEstudiante,
        ];

        try {
            $data = DB::select('exec aula.SP_UPD_tareaEstudiantesxEntregarEstudianteTarea
                ?,?,?', $parametros);

            if ($data[0]->iTareaEstudianteId > 0) {

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

    public function eliminarEstudianteTarea(Request $request)
    {
        $fieldsToDecode = [
            'iTareaEstudianteId',
            'iTareaId',
            'iEstudianteId',
            'iEscalaCalifId',
            'iTareaCabGrupoId',
        ];
        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->iTareaEstudianteId                    ??      NULL,
            $request->iTareaId                              ??      NULL,
            $request->iEstudianteId                         ??      NULL,
            $request->iEscalaCalifId                        ??      NULL,
            $request->nTareaEstudianteNota                  ??      NULL,
            $request->cTareaEstudianteComentarioDocente     ??      NULL,
            $request->cTareaEstudianteUrlEstudiante         ??      NULL,
            $request->iEstado                               ??      NULL,
            $request->iSesionId                             ??      NULL,
            $request->dtCreado                              ??      NULL,
            $request->dtActualizado                         ??      NULL,
            $request->iTareaCabGrupoId                      ??      NULL

            //$request->iCredId

        ];

        try {
            $data = DB::select('exec aula.SP_UPD_tareaEstudiantes
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            if ($data[0]->iTareaEstudianteId > 0) {

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
