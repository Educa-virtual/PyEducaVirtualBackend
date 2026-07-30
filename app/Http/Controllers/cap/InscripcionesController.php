<?php

namespace App\Http\Controllers\cap;

use App\Helpers\VerifyHash;
use App\Http\Controllers\api\grl\PersonaController;
use App\Http\Controllers\Controller;
use App\Mail\cap\EstadoInscripcionMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class InscripcionesController extends Controller
{
    // Notas: Campo iEstado
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
                'iPersId',
            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $data = new PersonaController;
            $data = ($data->buscarPersona($request))->getContent();

            $data = json_decode($data, true);

            if (isset($data['data']['iPersId'])) {
                $request->merge(['iPersId' => $data['data']['iPersId']]);
                $request = VerifyHash::validateRequest($request, $fieldsToDecode);

                $datosContacto = DB::select(
                    '
                    SELECT 
                     cPersTelefono
                    ,cPersCorreo
                    FROM grl.personas
                    WHERE iPersId = ' . $data['data']['iPersId']
                );

                $data['data']['cPersTelefono'] = count($datosContacto) > 0 ? $datosContacto[0]->cPersTelefono : null;
                $data['data']['cPersCorreo'] = count($datosContacto) > 0 ? $datosContacto[0]->cPersCorreo : null;

                $parametros = [
                    $request->iPersId ?? null,
                    $request->iCapacitacionId ?? null,
                    $request->iCredId ?? null,

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
            $instituciones = DB::select('
            SELECT ie.iIieeId, ie.cIieeCodigoModular, ie.cIieeNombre, nt.cNivelTipoNombre
                FROM acad.institucion_educativas AS ie
                INNER JOIN acad.nivel_tipos AS nt ON nt.iNivelTipoId = ie.iNivelTipoId
                WHERE ie.iEstado = 1
            ');

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
                'iCredId',
            ];

            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCapacitacionId ?? null,
                $request->iPersId ?? null,
                $request->cInscripCorreo ?? null,
                $request->cInscripCel ?? null,
                $request->iIieeId ?? null,
                $request->cVoucher ?? null,
                $request->iCredId ?? null,
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
                DB::update('
                    UPDATE grl.personas
                    SET 
                    cPersDomicilio = ?,
                    cPersTelefono  = ?,
                    cPersCorreo    = ?
                    WHERE iPersId = ?
                ', [$request->cPersDomicilio, $request->cInscripCel, $request->cInscripCorreo, $request->iPersId]);

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
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCapacitacionId ?? null,
                $request->iCredId ?? null,
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
            'iInscripId' => ['required'],
            'bEstado' => ['required'],
        ], [
            'iInscripId.required' => 'No se encontró el identificador iInscripId',
            'bEstado.required' => 'No se encontró el estado',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $iEstado = $request->bEstado ? 10 : 100;
        $request->merge(['iEstado' => $iEstado]);

        try {
            $fieldsToDecode = [
                'iInscripId',
                'iCredId',
            ];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iInscripId ?? null,
                $request->iEstado ?? null,
                $request->iCredId ?? null,
            ];

            $data = DB::select(
                'exec cap.SP_UPD_inscripcionesxiInscripIdxiEstado
                    @_iInscripId=?, 
                    @_iEstado=?,
                    @_iCredId=?',
                $parametros
            );
            $cEstado = $request->bEstado ? 'validado' : 'rechazado';

            if ($data[0]->iInscripId > 0) {
                $message = 'Se ha ' . $cEstado . ' correctamente a la Inscripción';

                $info = DB::select(
                    'exec cap.SP_SEL_detalleInscripcion ?',
                    [$request->iInscripId]
                );

                if (count($info) > 0) {
                    $participante = (object) [
                        'cPersNombre' => $info[0]->cPersNombre,
                        'cPersCorreo' => $info[0]->cInscripCorreo,
                    ];
                    $capacitacion = (object) [
                        'cCapacitacionNombre' => $info[0]->cCapTitulo,
                    ];

                    if (! empty($participante->cPersCorreo)) {
                        $estadoTexto = $request->bEstado ? 'aprobado' : 'rechazado';
                        try {
                            Mail::mailer('mailer_capacitaciones')
                                ->to($participante->cPersCorreo)
                                ->send(new EstadoInscripcionMail($participante, $capacitacion, $estadoTexto));
                        } catch (\Exception $e) {
                            return new JsonResponse(
                                ['validated' => false, 'message' => $e->getMessage(), 'data' => []],
                                Response::HTTP_INTERNAL_SERVER_ERROR
                            );
                        }
                    }
                }

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
