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

        foreach (['valorBusqueda', 'iEvalPregId', 'iEvaluacionId', 'iBancoId'] as $f) {
            $request[$f] = $this->decodeValue($request->$f);
        }

        $iCredId = $request->iCredId ?? 0; 
        if ($iCredId === null) {
            $iCredId = 0; 
        }

        return [
            'opcion' => $request->opcion,
            'valorBusqueda' => $request->valorBusqueda ?? '-',
            'iPreguntaId' => $request->iPreguntaId ?? null,
            'iDesempenoId' => $request->iDesempenoId ?? null,
            'iTipoPregId' => $request->iTipoPregId ?? null,
            'cPregunta' => $request->cPregunta ?? null,
            'cPreguntaTextoAyuda' => $request->cPreguntaTextoAyuda ?? null,
            'iPreguntaNivel' => $request->iPreguntaNivel ?? null,
            'iPreguntaPeso' => $request->iPreguntaPeso ?? null,
            'dtPreguntaTiempo' => $request->dtPreguntaTiempo ?? null,
            'bPreguntaEstado' => $request->bPreguntaEstado ?? 1,
            'cPreguntaClave' => $request->cPreguntaClave ?? null,
            'iEspecialistaId' => $request->iEspecialistaId ?? null,
            'iNivelGradoId' => $request->iNivelGradoId ?? null,
            'iEncabPregId' => $request->iEncabPregId ?? null,
            'iCursosNivelGradId' => $request->iCursosNivelGradId ?? null,
            'iCredId' => $iCredId, 
        ];
    }

    public function handleCrudOperation(Request $request)
    {
        $params = $this->validateRequest($request);

        switch ($request->opcion) {
            case 'GUARDAR-PREGUNTAS':
                try {
                    $sql = "
                    DECLARE @result INT
                    EXEC @result = ere.Sp_INS_preguntas 
                        @_opcion = :opcion, 
                        @_valorBusqueda = :valorBusqueda,
                        @_iPreguntaId = :iPreguntaId,
                        @_iDesempenoId = :iDesempenoId,
                        @_iTipoPregId = :iTipoPregId,
                        @_cPregunta = :cPregunta,
                        @_cPreguntaTextoAyuda = :cPreguntaTextoAyuda,
                        @_iPreguntaNivel = :iPreguntaNivel,
                        @_iPreguntaPeso = :iPreguntaPeso,
                        @_dtPreguntaTiempo = :dtPreguntaTiempo,
                        @_bPreguntaEstado = :bPreguntaEstado,
                        @_cPreguntaClave = :cPreguntaClave,
                        @_iEspecialistaId = :iEspecialistaId,
                        @_iNivelGradoId = :iNivelGradoId,
                        @_iEncabPregId = :iEncabPregId,
                        @_iCursosNivelGradId = :iCursosNivelGradId,
                        @_iCredId = :iCredId;
                    SELECT @result AS result;
                    ";

                    $data = DB::select($sql, $params);

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