<?php

namespace App\Models\eval;

use App\DTO\WhereCondition;
use App\Repositories\GeneralRepository;
use Illuminate\Database\Eloquent\Model;

class EvaluacionRespuesta extends Model
{
    protected $table = 'evaluacion_respuestas';
    protected $schema = 'eval';

    public function guardar($data) {}

    public function actualizar($jsonData, $jsonWhere)
    {
        return GeneralRepository::actualizar($this->schema, $this->table, $jsonData, $jsonWhere);
    }

    public static function marcarComoCalificado(int $iEvalRptaId): void
    {
        $evaluacionRespuesta = new EvaluacionRespuesta();
        $dataJson = json_encode([
            'iEstado' => 2
        ]);
        $whereJson = json_encode([
            new WhereCondition('iEvalRptaId', $iEvalRptaId)
        ]);
        $evaluacionRespuesta->actualizar($dataJson, $whereJson);
    }
}
