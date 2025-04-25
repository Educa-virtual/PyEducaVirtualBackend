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
use App\Http\Controllers\grl\PersonasContactosController;

class InscripcionesController extends Controller
{   
    //Notas: Campo iEstado
    // 0 => Eliminado
    // 1 => Inscrito
    // 10 => Validado
    // 100 => Rechazado

    public function listarPersonaInscripcion(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iTipoIdentId',
                'iCapacitacionId',
                'iPersId'
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $data = new PersonaController();
            $data = $data->validate($request)->getData(true);
            if (isset($data['data']['iPersId'])) {
                $request->merge(['iPersId' => $data['data']['iPersId']]);
                $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

                $contacto = new PersonasContactosController();
                $contacto = $contacto->obtenerxiPersId($request)->getData(true);
                $contacto = $contacto['data'];
                $data['data']['cPersCel'] = $contacto['cPersCel'];
                $data['data']['cPersCorreo'] = $contacto['cPersCorreo'];

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
           
            if ($data[0]->iInscripId>0) {
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
    public function listarInscripcionesxiCapacitacionId(Request $request) {
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
}
