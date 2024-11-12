<?php

namespace App\Models\eval;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InstrumentoEvaluacion extends Model
{
    public function obtener(array $params)
    {
        $params = [
            $params['iInstrumentoId'] ?? 0,
            $params['iDocenteId'] ?? 0,
            $params['idDocCursoId'] ?? 0,
            $params['iCursoId'] ?? 0,
            $params['busqueda'] ?? '',
            $params['ínstrumentoIdSeleccionado'] ?? 0,
        ];

        $data = DB::select('exec eval.SP_SEL_instrumentoEvaluaciones
                @_iInstrumentoId = ?
                ,@_iDocenteId = ?
                ,@_idDocCursoId = ?
                ,@_iCursoId = ?
                ,@_busqueda = ?
                ,@_iInstrumentoIdSeleccionado = ?
            ', $params);

        foreach ($data as $key => $item) {
            $criterios = $item->criterios ?? '[]';
            $data[$key]->criterios  = json_decode($criterios, true);
        }

        return $data;
    }
}
