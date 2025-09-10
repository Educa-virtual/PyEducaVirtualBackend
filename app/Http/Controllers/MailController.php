<?php

namespace App\Http\Controllers;

use App\Mail\CodigoMail;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{

    public function comparar(Request $request)
    {
        $cCodeVerif = $request->cCodeVerif;
        $iPersId = $request->iPersId;
        $data = DB::update("UPDATE seg.credenciales
                            SET bCredVerificado = 1
                            WHERE iPersId = ? AND cCredCodigoVerif = ?", [$iPersId, $cCodeVerif]);

        try {
            $response = [
                'validated' => $data ? true : false,
                'message' => '',
                'data' => [],
            ];

            $estado = 200;
        } catch (Exception $e) {
            $response = [
                'validated' => true,
                'message' => $e->getMessage(),
                'data' => [],
            ];

            $estado = 500;
        }
        return new JsonResponse($response, $estado);
    }

    public function enviarMailCodVerificarCorreo(Request $request)
    {

        $logo_seguridad = "data:image/png;base64,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";

        $mailData = [
            'title' => 'CÓDIGO DE VERIFICACIÓN',
            'body'  => $request->cPersConCodigoValidacion,
            'logo'  => $logo_seguridad,
        ];

        try {
           Mail::to($request->cPersCorreo)->send(new CodigoMail($mailData));

           $response = [
                'validated' => true,
                'message' => 'Email enviado.',
                'data' => $request->iPersConId,
            ];

            $estado = 200;
        } catch (Exception $e) {
            $response = [
                'validated' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ];

            $estado = 500;
        }
        return new JsonResponse($response, $estado);
    }
}
