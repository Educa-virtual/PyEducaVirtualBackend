<?php

namespace App\Http\Requests\Ere;

use App\Http\Requests\GeneralFormRequest;

class GuardarHojaDesarrolloEstudianteRequest extends GeneralFormRequest
{
    public function rules(): array
    {
        return [
            'iEvaluacionId' => 'required|string',
            'iCursosNivelGradId' => 'required|string',
            'iEstudianteId' => 'required|integer',
            'archivo' => 'file|mimetypes:application/octet-stream,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,application/pdf,image/jpeg,image/png|max:10240',

        ];
    }

    public function attributes(): array
    {
        return [
            'iEvaluacionId' => 'ID de evaluacion',
            'iCursosNivelGradId' => 'ID de curso',
            'iEstudianteId' => 'ID de estudiante',
            'archivo' => 'Archivo'
        ];
    }
}
