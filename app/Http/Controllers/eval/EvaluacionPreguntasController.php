<?php

namespace App\Http\Controllers\eval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class EvaluacionPreguntasController extends Controller
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(
            config('hashids.salt'),
            config('hashids.min_length')
        );
    }

    private function decodeValue($value)
    {
        if (is_null($value)) {
            return null;
        }
        return is_numeric($value)
            ? $value
            : ($this->hashids->decode($value)[0] ?? null);
    }

    private function validateRequest(Request $request)
    {
        $request->validate(
            ['opcion' => 'required'],
            ['opcion.required' => 'Hubo un problema al obtener la acción']
        );

        foreach (['valorBusqueda','iEvalPregId','iEvaluacionId','iBancoId'] as $f) {
            $request[$f] = $this->decodeValue($request->$f);
        }

        return [
            $request->opcion,
            $request->valorBusqueda           ?? '-',
            $request->iPreguntaId             ?? null,
            $request->iDesempenoId            ?? null,
            $request->iTipoPregId             ?? null,
            $request->cPregunta               ?? null,
            $request->cPreguntaTextoAyuda     ?? null,
            $request->iPreguntaNivel          ?? null,
            $request->iPreguntaPeso           ?? null,
            $request->dtPreguntaTiempo        ?? null,
            $request->bPreguntaEstado         ?? 1,
            $request->cPreguntaClave          ?? null,
            $request->iEspecialistaId         ?? null,
            $request->iNivelGradoId           ?? null,
            $request->iEncabPregId            ?? null,
            $request->iCursosNivelGradId      ?? null,
            $request->iCredId                 ?? null,
            $request->iPreguntaOrden          ?? 0,
        ];
    }

    public function handleCrudOperation(Request $request)
    {
        $params = $this->validateRequest($request);
        $bindings = array_values($params);

        switch ($request->opcion) {
            case 'GUARDAR-PREGUNTAS':
                $placeholders = implode(',', array_fill(0, count($bindings), '?'));
                $sql = "EXEC ere.Sp_INS_preguntas {$placeholders}";

                try {
                    $data = DB::select($sql, $bindings);

                    return new JsonResponse([
                        'validated' => true,
                        'message'   => 'Pregunta guardada',
                        'data'      => $data
                    ], 200);

                } catch (\Exception $e) {
                    return new JsonResponse([
                        'validated' => false,
                        'message'   => $e->getMessage(),
                        'data'      => []
                    ], 500);
                }

            default:
                return new JsonResponse([
                    'validated' => false,
                    'message'   => 'Opción no válida',
                    'data'      => []
                ], 400);
        }
    }
}
