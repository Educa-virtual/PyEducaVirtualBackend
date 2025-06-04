<?php

namespace App\Http\Controllers\seg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\CodigoMail;
use App\Helpers\VerifyHash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class CredencialesController extends Controller
{
    public function  obtenerUsuario(Request $request)
    {
        try {
            $data = DB::select("
                SELECT 
                    MAX(c.iCredId) AS iCredId
                    FROM seg.credenciales AS c
                    INNER JOIN grl.personas_contactos AS pc ON pc.iPersId = c.iPersId
                    WHERE c.cCredUsuario = '" . $request->cUsuario . "' AND pc.cPersConNombre = '" . $request->cCorreo . "' AND pc.iTipoConId = 1
            ");

            if ($data[0]->iCredId > 0) {
                $cCredTokenPassword = mt_rand(100000, 999999);

                $logo_seguridad = "data:image/png;base64,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";
                $mailData = [
                    'title' => 'CÓDIGO DE VERIFICACIÓN',
                    'body'  => $cCredTokenPassword,
                    'logo'  => $logo_seguridad,
                ];

                Mail::to($request->cCorreo)->send(new CodigoMail($mailData));
                DB::update('update seg.credenciales set cCredTokenPassword = ? where iCredId = ?', [$cCredTokenPassword, $data[0]->iCredId]);

                $response = ['validated' => true, 'mensaje' => 'se obtuvo la información', 'data' => null];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido obtener la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function  verificarUsuario(Request $request)
    {
        try {
            $data = DB::select("
                SELECT 
                    MAX(c.iCredId) AS iCredId
                    FROM seg.credenciales AS c
                    INNER JOIN grl.personas_contactos AS pc ON pc.iPersId = c.iPersId
                    WHERE c.cCredUsuario = '" . $request->cUsuario . "' AND pc.cPersConNombre = '" . $request->cCorreo . "' AND c.cCredTokenPassword ='" . $request->cCredTokenPassword . "'  AND pc.iTipoConId = 1
            ");

            if ($data[0]->iCredId > 0) {
                $response = ['validated' => true, 'mensaje' => 'se obtuvo la información', 'data' => null];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido obtener la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function  actualizarUsuario(Request $request)
    {
        try {
            $data = DB::select("
                SELECT 
                    MAX(c.iCredId) AS iCredId
                    FROM seg.credenciales AS c
                    INNER JOIN grl.personas_contactos AS pc ON pc.iPersId = c.iPersId
                    WHERE c.cCredUsuario = '" . $request->cUsuario . "' AND pc.cPersConNombre = '" . $request->cCorreo . "' AND pc.iTipoConId = 1
            ");

            if ($data[0]->iCredId > 0) {
                DB::update('update seg.credenciales set password = ? where iCredId = ?', [sha1($request->cPassword), $data[0]->iCredId]);

                $response = ['validated' => true, 'mensaje' => 'se actualizó la información', 'data' => null];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido actualizar la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contraseniaNueva' => [
                'nullable',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ]
        ], [
            'contraseniaNueva.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'contraseniaNueva.regex' => 'La contraseña debe contener una mayúscula, una minúscula y un número.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors() // Devuelve MessageBag
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $fieldsToDecode = [
            'iPersId',
            'iCredId',
        ];

        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->iCredId,
            $request->iPersId,
            $request->contraseniaActual,
            $request->contraseniaNueva
        ];

        try {
            $data = DB::select("execute seg.Sp_UPD_credenciasxUpdatePassword ?,?,?,?", $parametros);

            if ($data[0]->iPersId > 0) {
                return new JsonResponse(
                    ['validated' => true, 'message' => 'Se actualizó la contraseña exitosamente '],
                    Response::HTTP_OK
                );
            } else {
                return new JsonResponse(
                    ['validated' => false, 'message' => 'No se pudo actualizar la contraseña'],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54)],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new JsonResponse($response, $codeResponse);
    }
}
