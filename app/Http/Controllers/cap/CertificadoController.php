<?php

namespace App\Http\Controllers\cap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Support\Facades\DB;
use Exception;

class CertificadoController extends Controller
{
    public function downloadPdf($iCapacitacionId, $iPersId, Request $request)
    {
        $request->merge(['iCapacitacionId' => $iCapacitacionId]);
        $request->merge(['iPersId' => $iPersId]);

        $validator = Validator::make($request->all(), [
            'iCapacitacionId' => ['required'],
            'iPersId' => ['required'],
        ], [
            'iCapacitacionId.required' => 'No se encontró la capacitación',
            'iPersId.required' => 'No se encontró el identificador de la persona',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        try {
            $fieldsToDecode = [
                'iCapacitacionId',
                'iPersId'
            ];

            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCapacitacionId      ??  NULL,
                $request->iPersId              ??  NULL
            ];

            $data = DB::select(
                'exec cap.SP_SEL_certificadoxiCapacitacionIdxiPersId
                    @_iCapacitacionId=?, 
                    @_iPersId=?',
                $parametros
            );

            if (count($data) > 0) {
                $data = $data[0];
                if (isset($data->bFinalizado) && $data->bFinalizado == 1) {
                    $html = view('cap.certificado', compact('data'))->render();
                    $pdf = PDF::loadHTML($html)->setPaper('a4', 'landscape');
                    return $pdf->stream("certificado_{$request->iCapacitacionId}.pdf");
                } else {
                    $message = 'La capacitación aún está en proceso, no se puede emitir el certificado.';
                    return new JsonResponse(
                        ['validated' => false, 'message' => $message, 'data' => []],
                        Response::HTTP_OK
                    );
                }

                // $contenido = "Participante: {$data->cNombres}\nCapacitación: {$data->cCapTitulo}\nHoras: {$data->iTotalHrs}";

            } else {
                $message = 'No se ha encontrado el certificado para la capacitación y persona proporcionadas.';
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
