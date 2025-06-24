<?php

namespace App\Http\Controllers\api\acad;

use App\Helpers\CollectionStrategy;
use App\Helpers\ResponseHandler;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DistribucionBloqueController extends Controller
{

  const schema = 'acad';

  public function getDistribucionBloques(Request $request)
  {
    try {

      $query = DB::select('EXEC acad.SP_SEL_stepCalendarioAcademicoDesdeJsonOpcion @json = :json, @_opcion = :opcion', [
        'json' => json_encode([
          'iYearId' => $request->route('iYearId'),
        ]),
        'opcion' => 'getDistribucionSemanas'
      ]);


      return ResponseHandler::success($query, 'Distribución de bloques obtenidos correctamente.');
    } catch (\Exception $e) {
      return ResponseHandler::error(
        'Error al obtener la distribución de bloques.',
        500,
        $e->getMessage()
      );
    }
  }



  public function insDistribucionBloques(Request $request)
  {
    $query = DB::select('EXEC acad.SP_INS_stepCalendarioAcademicoDesdeJsonOpcion @json = :json, @_opcion = :opcion', [
      'json' => json_encode([
        'iYearId' => $request->input('iYearId'),
        'iSesionId' => $request->input('iSesionId'),
        'iTipoDistribucionId' => $request->input('iTipoDistribucionId'),
        'dtInicioBloque' => $request->input('dtInicioBloque'),
        'dtFinBloque' => $request->input('dtFinBloque'),
        'iEstado' => $request->input('iEstado'),
      ]),
      'opcion' => 'agregarBloqueDistribucion',
    ]);

    return ResponseHandler::success($query, 'Bloque agregado correctamente.');  
  }

  public function updDistribucionBloques(Request $request)
  {
    $query = DB::select('EXEC acad.SP_INS_stepCalendarioAcademicoDesdeJsonOpcion @json = :json, @_opcion = :opcion', [
      'json' => json_encode([
        'iDistribucionBloqueId' => $request->input('iDistribucionBloqueId'),
        'iYearId' => $request->input('iYearId'),
        'iSesionId' => $request->input('iSesionId'),
        'iTipoDistribucionId' => $request->input('iTipoDistribucionId'),
        'dtInicioBloque' => $request->input('dtInicioBloque'),
        'dtFinBloque' => $request->input('dtFinBloque'),
        'iEstado' => $request->input('iEstado'),
      ]),
      'opcion' => 'agregarBloqueDistribucion',
    ]);

    return ResponseHandler::success($query, 'Bloque actualizado correctamente.');
  }

  public function deleteDistribucionBloques(Request $request)
  {
    $query = DB::select('EXEC acad.SP_DEL_stepCalendarioAcademicoDesdeJsonOpcion @json = :json, @_opcion = :opcion', [
      'json' => json_encode([
        'iDistribucionBloqueId' => $request->route('iDistribucionBloqueId'),
      ]),
      'opcion' => 'deleteBloqueDistribucion',
    ]);

    return ResponseHandler::success($query, 'Bloque eliminado correctamente.');
  }
}
