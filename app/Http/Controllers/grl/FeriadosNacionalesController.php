<?php

namespace App\Http\Controllers\grl;

use App\Helpers\CollectionStrategy;
use App\Helpers\ResponseHandler;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class FeriadosNacionalesController extends Controller
{
  const schema = 'grl';

  public function getFeriadosNacionales(Request $request)
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
        'tabla' => 'feriados_nacionales',
        'campos' => '*',
        'where' => $where,
      ]);

      $strategy = new CollectionStrategy();
      $apiController = new ApiController($strategy);
      $query = $apiController->getData($request);

      if ($query instanceof Collection) {
        $query = $query->sortByDesc('dtFeriado')->values();
      }

      return ResponseHandler::success($query, 'Feriados nacionales obtenidos correctamente.');
    } catch (\Exception $e) {
      return ResponseHandler::error(
        'Error al obtener los feriados nacionales.',
        500,
        $e->getMessage()
      );
    }
  }


  public function insFeriadosNacionales(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        'cFeriadoNombre' => 'required',
        'dtFeriado' => 'required',
        'bFeriadoEsRecuperable' => 'required',
      ],
      [
        'cFeriadoNombre.required' => 'El nombre del feriado es obligatorio.',
        'dtFeriado.required' => 'La fecha del feriado es obligatoria.',
        'bFeriadoEsRecuperable.required' => 'Debe indicar si el feriado es recuperable.',
      ]
    );

    if ($validator->fails()) {
      return new JsonResponse(['validated' => false, 'message' => $validator->errors(), 'data' => []], 422);
    }

    $query = DB::select("EXEC acad.SP_INS_stepCalendarioAcademicoDesdeJsonOpcion @json = :json, @_opcion = :opcion", [
      'json' => json_encode([
        'cFeriadoNombre' => $request->input('cFeriadoNombre'),
        'dtFeriado' => $request->input('dtFeriado'),
        'cFeriadoDescripcion' => $request->input('cFeriadoNombre'),
        'bFeriadoEsRecuperable' => $request->input('bFeriadoEsRecuperable'),
        'cDocumento' => $request->input('cDocumento'),
        'iYearId' => $request->input('iYearId'),
        'iEstado' => "1",
      ]),
      'opcion' => 'addFechasEspeciales',
    ]);

    return new JsonResponse(['validated' => true, 'message' => 'Feriados nacionales obtenidos exitosamente.', 'data' => $query], 200);
  }

  public function insFeriadosNacionalesMasivo(Request $request)
  {
    $request->merge(['json' => $request->all()]);

    $validator = Validator::make(
      $request->all(),
      [
        'json.*.cFeriadoNombre' => 'required',
        'json.*.dtFeriado' => 'required|date',
        'json.*.bFeriadoEsRecuperable' => 'required|boolean',
        'json.*.iYearId' => 'required|integer',
      ],
      [
        'json.*.cFeriadoNombre.required' => 'El nombre del feriado es obligatorio.',
        'json.*.dtFeriado.required' => 'La fecha del feriado es obligatoria.',
        'json.*.dtFeriado.date' => 'La fecha del feriado debe ser una fecha válida.',
        'json.*.bFeriadoEsRecuperable.required' => 'Debe indicar si el feriado es recuperable.',
        'json.*.bFeriadoEsRecuperable.boolean' => 'El valor de recuperable debe ser verdadero o falso.',
        'json.*.iYearId.required' => 'El año académico es obligatorio.',
        'json.*.iYearId.integer' => 'El año académico debe ser un número entero.',
      ]
    );

    if ($validator->fails()) {
      return new JsonResponse(['validated' => false, 'message' => $validator->errors(), 'data' => []], 422);
    }

    $query = DB::select(
      'EXEC acad.SP_INS_stepCalendarioAcademicoDesdeJsonOpcion @json = :json, @_opcion = :opcion',
      [
        'json' => json_encode($request->json),
        'opcion' => 'addFechasEspecialesMasivo'
      ]
    );

    // Implementar la lógica para insertar feriados nacionales masivos
    return ResponseHandler::success($query, 'Feriados nacionales masivos insertados correctamente.');
  }

  public function updFeriadosNacionales(Request $request)
  {
    $query = DB::select("EXEC acad.SP_INS_stepCalendarioAcademicoDesdeJsonOpcion @json = :json, @_opcion = :opcion", [
      'json' => json_encode([
        'iFeriadoId' => $request->input('iFeriadoId'),
        'cFeriadoNombre' => $request->input('cFeriadoNombre'),
        'dtFeriado' => $request->input('dtFeriado'),
        'cFeriadoDescripcion' => $request->input('cFeriadoNombre'),
        'bFeriadoEsRecuperable' => $request->input('bFeriadoEsRecuperable'),
        'cDocumento' => $request->input('cDocumento'),
        'iYearId' => $request->input('iYearId'),
        'iEstado' => "1",
      ]),
      'opcion' => 'editFechasEspeciales',
    ]);

    return new JsonResponse(['validated' => true, 'message' => 'Feriados nacionales obtenidos exitosamente.', 'data' => $query], 200);
  }

  public function syncFeriadosNacionales(Request $request)
  {
    $query = DB::select("EXEC acad.SP_INS_stepCalendarioAcademicoDesdeJsonOpcion @json = :json, @_opcion = :opcion", [
      'json' => json_encode([
        'iYearId' => $request->input('iYearId'),
      ]),
      'opcion' => 'syncFechasEspeciales ',
    ]);

    return new JsonResponse(['validated' => true, 'message' => 'Feriados nacionales obtenidos exitosamente.', 'data' => $query], 200);
  }

  public function deleteFeriadosNacionales(Request $request)
  {
    $iFeriadoId = $request->route('iFeriadoId');

    $validator = Validator::make(
      ['iFeriadoId' => $iFeriadoId],
      ['iFeriadoId' => 'required|integer'],
      ['iFeriadoId.required' => 'El ID del feriado es obligatorio.', 'iFeriadoId.integer' => 'El ID del feriado debe ser un número entero.']
    );

    if ($validator->fails()) {
      return response()->json([
        'validated' => false,
        'message' => $validator->errors(),
        'data' => [],
      ], 422);

      return new JsonResponse(['validated' => false, 'message' => $validator->errors(), 'data' => []], 422);
    }

    $query = DB::statement(
      'EXEC grl.SP_DEL_feriados_nacionalesXiFeriadoId @iFeriadoId = :iFeriadoId',
      [
        'iFeriadoId' => $iFeriadoId,
      ]
    );

    return new JsonResponse(['validated' => true, 'message' => 'Feriado eliminado correctamente.', 'data' => $query], 200);
  }
}
