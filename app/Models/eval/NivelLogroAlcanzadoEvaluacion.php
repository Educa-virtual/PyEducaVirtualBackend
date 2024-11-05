<?php

namespace App\Models\eval;

use App\Repositories\GeneralRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class NivelLogroAlcanzadoEvaluacion extends Model
{

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
}
