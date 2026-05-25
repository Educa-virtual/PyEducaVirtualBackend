<?php

namespace App\Http\Requests\Ere;

use App\Http\Requests\GeneralFormRequest;

class ActualizarEvaluacionRequest extends GeneralFormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'iEvaluacionId' => $this->route('iEvaluacionId'),
        ]);
    }

    public function rules(): array
    {
        return [
            'iEvaluacionId' => 'required|integer',
            'idTipoEvalId' => 'nullable|integer',
            'iNivelEvalId' => 'nullable|integer',
            'cEvaluacionNombre' => 'nullable|string|max:255',
            'cEvaluacionDescripcion' => 'nullable|string|max:255',
            'cEvaluacionUrlDrive' => 'nullable|string|max:255',
            'dtEvaluacionFechaInicio' => 'nullable|string',
            'dtEvaluacionFechaFin' => 'nullable|string',

        ];
    }

    public function attributes(): array
    {
        return [
            'iEvaluacionId' => 'Id de evaluación',
            'idTipoEvalId' => 'tipo',
            'iNivelEvalId' => 'nivel',
            'cEvaluacionNombre' => 'nombre',
            'cEvaluacionDescripcion' => 'descripción',
            'cEvaluacionUrlDrive' => 'enlace a carpeta compartida',
            'dtEvaluacionFechaInicio' => 'fecha de inicio',
            'dtEvaluacionFechaFin' => 'fecha de fin',
        ];
    }
}
