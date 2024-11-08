<?php

namespace App\Models\eval;

use App\DTO\WhereCondition;
use App\Repositories\GeneralRepository;
use App\Traits\HashidsTrait;
use App\Traits\HelperTrait;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class NivelLogroAlcanzadoEvaluacion extends Model
{
    use HashidsTrait;

    public function __construct()
    {
        $this->initializeHashids();
    }

    protected $schema = 'eval';
    protected $table = 'nivel_logro_alcanzado_evaluaciones';

    public function guardar($dataJson)
    {
        return GeneralRepository::insertar($this->schema, $this->table, $dataJson);
    }

    public function actualizar($dataJson, $wheJson)
    {
        return GeneralRepository::actualizar($this->schema, $this->table, $dataJson, $wheJson);
    }


    public  function calificarLogros(array $logros, int $iEvalRptaId, bool $esRubrica = false): array
    {
        $logro = null;

        try {
            foreach ($logros as &$logro) {
                $this->procesarLogro($logro, $esRubrica);
            }

            EvaluacionRespuesta::marcarComoCalificado($iEvalRptaId);

            return $logros;
        } catch (Exception $e) {
            throw $e;
        } finally {
            if (isset($logro)) {
                unset($logro);
            }
        }
    }

    private  function procesarLogro(array &$logro, bool $esRubrica): void
    {

        $iNivelLogroAlcId =  $this->decodeId($logro['iNivelLogroAlcId'] ?? 0);
        $datosBase = [
            'cNivelLogroAlcConclusionDescriptiva' => $logro['cNivelLogroAlcConclusionDescriptiva'],
            'nNnivelLogroAlcNota' => $logro['nNnivelLogroAlcNota'],
            'iEscalaCalifId' => $logro['iEscalaCalifId'],
        ];

        if ($esRubrica) {
            $datosBase['iNivelEvaId'] = $logro['iNivelEvaId'];
        } else {
            $datosBase['iNivelLogroEvaId'] = $logro['iNivelLogroEvaId'];
        }

        if ($logro['iNivelLogroAlcId'] == 0) {
            $datosInsertar = array_merge($datosBase, ['iEvalRptaId' => $logro['iEvalRptaId']]);
            $resp = $this->guardar(json_encode($datosInsertar));
            $logro['iEvalRptaId'] = $resp[0]->id;
        } else {
            $whereJson = json_encode([
                new WhereCondition('iNivelLogroAlcId', $iNivelLogroAlcId)
            ]);
            $this->actualizar(json_encode($datosBase), $whereJson);
        }
    }
}
