<?php

namespace App\Http\Controllers\api\grl;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelPdf\Facades\Pdf;

class DiasController extends Controller
{
  public function list(Request $request)
  {
    $solicitud = [
      $request->json,
      $request->_opcion,
    ];

    // $json = '{"jmod": "grl", "jtable": "dias"}';
    // $consulta = "getConsulta";

    $query = DB::select("EXEC acad.Sp_ACAD_CRUD_CALENDARIO ?,?", 
    $solicitud);

    try {
      $response = [
        'validated' => true,
        'message' => 'se obtuvo la información',
        'data' => $query,
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
}
