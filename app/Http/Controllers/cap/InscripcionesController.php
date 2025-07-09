<?php

namespace App\Http\Controllers\cap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;
use App\Http\Controllers\api\grl\PersonaController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\grl\PersonasController;

class InscripcionesController extends Controller
{
    //Notas: Campo iEstado
    // 0 => Eliminado
    // 1 => Inscrito
    // 10 => Validado
    // 100 => Rechazado

    public function buscarPersonaInscripcion(Request $request, $iCapacitacionId, $iTipoIdentId, $cPersDocumento)
    {
        $request->merge(['iCapacitacionId' => $iCapacitacionId]);
        $request->merge(['iTipoIdentId' => $iTipoIdentId]);
        $request->merge(['cPersDocumento' => $cPersDocumento]);

        try {
            $fieldsToDecode = [
                'iTipoIdentId',
                'iCapacitacionId',
                'iPersId'
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $data = new PersonaController();
            $data = ($data->buscarPersona($request))->getContent();
            $data = json_decode($data, true);

            if (isset($data['data']['iPersId'])) {
                $request->merge(['iPersId' => $data['data']['iPersId']]);
                $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

                $data['data']['cPersCel'] = $data['data']['cPersConTelefonoMovil'];
                $data['data']['cPersCorreo'] = $data['data']['cPersConCorreoElectronico'];

                $parametros = [
                    $request->iPersId              ??  NULL,
                    $request->iCapacitacionId      ??  NULL,
                    $request->iCredId              ??  NULL

                ];
                $inscripciones = DB::select(
                    'exec cap.SP_SEL_inscripcionesxiPersIdxiCapacitacionId
                        @_iPersId=?,
                        @_iCapacitacionId=?,   
                        @_iCredId=?',
                    $parametros
                );

                if (count($inscripciones) > 0) {
                    if ($inscripciones[0]->iMatriculado) {
                        $message = $data['data']['cPersNombre'] . ' ' . $data['data']['cPersPaterno'] . ' ya se encuentra matriculado en la capacitación';
                        return new JsonResponse(
                            ['validated' => false, 'message' => $message, 'data' => []],
                            Response::HTTP_OK
                        );
                    } else {
                        $message = $data['data']['cPersNombre'] . ' ' . $data['data']['cPersPaterno'] . ' ya se encuentra inscrito en la capacitación';
                        return new JsonResponse(
                            ['validated' => false, 'message' => $message, 'data' => []],
                            Response::HTTP_OK
                        );
                    }
                }
            }
            $instituciones = DB::select("SELECT iIieeId, cIieeCodigoModular, cIieeNombre FROM acad.institucion_educativas WHERE iEstado = 1");
            return new JsonResponse(
                ['validated' => true, 'message' => 'Se ha obtenido exitosamente ', 'data' => $data['data'], 'instituciones' => $instituciones],
                Response::HTTP_OK
            );
            return $data;
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
    public function guardarInscripcion(Request $request)
    {
        if (!isset($request->iPersId)) {
            $persona = new PersonasController();
            $persona = $persona->guardarPersonas($request);

            if ($persona[0]->iPersId > 0) {
                $request->merge(['iPersId' => $persona[0]->iPersId]);
                $request->merge(['dPersNacimiento' => null]);
                $request->merge(['cPersFotografia' => null]);
                $request->merge(['cPersDomicilio' => $request->cPersDireccion]);
                $request->merge(['cPersCorreo' => $request->cInscripCorreo]);
                $request->merge(['cPersCelular' => $request->cInscripCel]);
                $datosPersonales = new PersonasController();
                $datosPersonales = $datosPersonales->guardarPersonasxDatosPersonales($request);
                $request->merge(['iPersId' => $persona[0]->iPersId]);
            } else {
                return response()->json([
                    'validated' => false,
                    'errors' => 'No se encontró el iPersId'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        try {
            $fieldsToDecode = [
                'iCapacitacionId',
                'iPersId',
                'iIieeId',
                'iCredId'
            ];

            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCapacitacionId      ??  NULL,
                $request->iPersId              ??  NULL,
                $request->cInscripCorreo       ??  NULL,
                $request->cInscripCel          ??  NULL,
                $request->iIieeId              ??  NULL,
                $request->cVoucher             ??  NULL,
                $request->iCredId              ??  NULL
            ];
            $data = DB::select(
                'exec cap.SP_INS_inscripciones
                    @_iCapacitacionId=?,
                    @_iPersId=?,
                    @_cInscripCorreo=?,
                    @_cInscripCel=?,
                    @_iIieeId=?,
                    @_cVoucher=?,
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iInscripId > 0) {
                $message = 'Se ha inscrito correctamente a la capacitación';
                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => $data],
                    Response::HTTP_OK
                );
            } else {
                $message = 'No se ha podido inscribir a la capacitación';
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
    public function listarInscripcionesxiCapacitacionId(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iInscripId',
                'iCapacitacionId',
                'iPersId',
                'iIieeId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCapacitacionId      ??  NULL,
                $request->iCredId              ??  NULL
            ];

            $data = DB::select(
                'exec cap.SP_SEL_inscripcionesxiCapacitacionId
                    @_iCapacitacionId=?, 
                    @_iCredId=?',
                $parametros
            );
            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);
            return new JsonResponse(
                ['validated' => true, 'message' => 'Se ha obtenido exitosamente ', 'data' => ($data)],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function actualizarEstadoInscripcion(Request $request, $iInscripId)
    {
        $request->merge(['iInscripId' => $iInscripId]);

        $validator = Validator::make($request->all(), [
            'iCapacitacionId' => ['required'],
            'bEstado' => ['required'],
        ], [
            'iCapacitacionId.required' => 'No se encontró el identificador iCapacitacionId',
            'bEstado.required' => 'No se encontró el estado',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $iEstado = $request->bEstado ? 10 : 100;
        $request->merge(['iEstado' => $iEstado]);


        try {
            $fieldsToDecode = [
                'iInscripId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iInscripId      ??  NULL,
                $request->iEstado         ??  NULL,
                $request->iCredId         ??  NULL
            ];

            $data = DB::select(
                'exec cap.SP_UPD_inscripcionesxiInscripIdxiEstado
                    @_iInscripId=?, 
                    @_iEstado=?,
                    @_iCredId=?',
                $parametros
            );
            $cEstado = $request->bEstado ? 'Validado' : 'Rechazado';

            if ($data[0]->iInscripId > 0) {
                $message = 'Se ha ' . $cEstado . ' correctamente a la Inscripción';
                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => $data],
                    Response::HTTP_OK
                );
            } else {
                $message = 'No se ha ' . $cEstado . ' correctamente a la Inscripción';
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
}
