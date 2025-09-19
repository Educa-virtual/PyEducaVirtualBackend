<?php

namespace App\Http\Requests\Ere;

use App\Http\Requests\GeneralFormRequest;

class HojaDesarrolloEstudianteRequest extends GeneralFormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'iEvaluacionId' => $this->route('iEvaluacionId'),
            'iCursosNivelGradId' => $this->route('iCursosNivelGradId'),
            'iEstudianteId' => $this->route('iEstudianteId'),
        ]);
    }

    public function rules(): array
    {
        return [
            'iEvaluacionId' => 'required|string',
            'iCursosNivelGradId' => 'required|string',
            'iEstudianteId' => 'required|integer',

        ];
    }

    public function attributes(): array
    {
        return [
            'iEvaluacionId' => 'ID de evaluacion',
            'iCursosNivelGradId' => 'ID de curso',
            'iEstudianteId' => 'ID de estudiante',
        ];
    }
}
