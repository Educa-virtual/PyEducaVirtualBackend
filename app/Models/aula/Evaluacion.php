<?php

namespace App\Models\aula;

use App\Repositories\GeneralRepository;
use Illuminate\Support\Facades\DB;
use Throwable;

class Evaluacion
{
    protected $schema = 'eval';
    protected $table = 'evaluacion_preguntas';

    public function guardarPreguntas($evaluacionId, $preguntas)
    {
        DB::beginTransaction();
        try {
            foreach ($preguntas as $key => $pregunta) {
                $preguntas[$key] = $this->procesarPregunta($evaluacionId, $pregunta);
            }

            $this->actualizarTotalPreguntas($evaluacionId, count($preguntas));

            DB::commit();
            return $preguntas;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function procesarPregunta($evaluacionId, $pregunta)
    {
        $camposJson = json_encode([
            'iEvaluacionId' => $evaluacionId,
            'iBancoId' => $pregunta['iPreguntaId']
        ]);

        if (!$this->existePregunta($evaluacionId, $pregunta['iPreguntaId'])) {
            $params = [
                $this->schema,
                $this->table,
                $camposJson
            ];

            $resp = DB::select('exec grl.SP_INS_EnTablaDesdeJSON @Esquema = ?, @Tabla = ?, @DatosJSON = ?', $params);
            $pregunta['newId'] = $resp[0]->id;
        }

        return $pregunta;
    }

    protected function existePregunta($evaluacionId, $bancoId)
    {
        $existe = DB::select(
            "select 1 from {$this->schema}.{$this->table} where iEvaluacionId = ? AND iBancoId = ?",
            [$evaluacionId, $bancoId]
        );
        return count($existe) > 0;
    }

    protected function actualizarTotalPreguntas($evaluacionId, $total)
    {
        $where = [
            [
                'COLUMN_NAME' => "iEvaluacionId",
                'VALUE' => $evaluacionId
            ]
        ];

        GeneralRepository::actualizar(
            'eval',
            'evaluaciones',
            json_encode(['iEvaluacionNroPreguntas' => $total]),
            json_encode($where)
        );
    }
}
