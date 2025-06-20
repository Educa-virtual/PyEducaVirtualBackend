<?php

namespace App\Http\Controllers\grl;

use App\Helpers\CollectionStrategy;
use App\Helpers\ResponseHandler;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class YearController extends Controller
{
  const schema = 'grl';

  public function getYears(Request $request)
  {
    try {

      $iYearId = $request->route('iYearId');

      if (!is_null($iYearId)) {
        $validator = Validator::make(
          ['iYearId' => $iYearId],
          ['iYearId' => 'integer'],
          ['iYearId.integer' => 'El año debe ser un número entero.']
        );

        if ($validator->fails()) {
          return response()->json([
            'validated' => false,
            'message' => $validator->errors(),
            'data' => [],
          ], 422);
        }

        $where = "iYearId = {$iYearId}";
      } else {
        $where = '1=1';
      }

      // Construimos el request modificado
      $request->replace([
        'esquema' => self::schema,
        'tabla' => 'years',
        'campos' => '*',
        'where' => $where,
      ]);

      $strategy = new CollectionStrategy();
      $apiController = new ApiController($strategy);
      $query = $apiController->getData($request);

      if ($query instanceof Collection) {
        $query = $query->sortByDesc('iYearId')->values();
      }

      return ResponseHandler::success($query, 'Años obtenidos correctamente.');
    } catch (\Exception $e) {
      return ResponseHandler::error(
        'Error al obtener los años.',
        500,
        $e->getMessage()
      );
    }
  }


  public function insYears(Request $request)
  {
    $query = DB::select(
      "EXEC grl.SP_INS_TablaYearXopcion @json = :json, @_opcion = :opcion",
      [
        'json' => json_encode(
          [
            'cYearNombre' => $request->input('cYearNombre'),
            'cYearOficial' => $request->input('cYearOficial'),
            'iYearEstado' => $request->input('iYearEstado'),
          ]
        ),
        'opcion' => 'addYear'
      ]
    );

    return ResponseHandler::success($query, 'Año registrado correctamente.');
  }

  public function updYears(Request $request)
  {
    $query = DB::select("EXEC grl.SP_UPD_TablaYearXopcion @json = :json, @_opcion = :opcion", [
      'json' => json_encode([
        'iYearId' => $request->input('iYearId'),
        'cYearNombre' => $request->input('cYearNombre'),
        'cYearOficial' => $request->input('cYearOficial'),
        'iYearEstado' => $request->input('iYearEstado'),
      ]),
      'opcion' => 'updateYear',
    ]);

    return ResponseHandler::success($query, 'Año actualizado correctamente.');
  }

  public function deleteYears(Request $request)
  {

    $query = DB::select("EXEC grl.SP_DEL_TablaYearXopcion @json = :json, @_opcion = :opcion", [
      'json' => json_encode([
        'iYearId' => $request->route('iYearId'),
      ]),
      'opcion' => 'deleteYear',
    ]);

    return ResponseHandler::success($query, 'Año eliminado correctamente.');
  }
}
