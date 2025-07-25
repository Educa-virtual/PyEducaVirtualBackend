<?php

namespace App\Http\Controllers\evaluaciones;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;

class EscalaCalificacionesController extends ApiController
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }
    public function index(Request $request)
    {
        $data =  DB::table('eval.escala_calificaciones')->get();
        foreach ($data as $key => $item) {
            $data[$key]->iEscalaCalifId = (int) $item->iEscalaCalifId;
        }
        return $this->successResponse($data, 'Datos obtenidos correctamente');
    }

    public function obtenerEscalaCalificaciones()
    {

        try {
            $data =  DB::select('
            SELECT
             iEscalaCalifId
            ,cEscalaCalifNombre
            FROM eval.escala_calificaciones
            ');

            $fieldsToDecode = [
                'iEscalaCalifId'
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
}
