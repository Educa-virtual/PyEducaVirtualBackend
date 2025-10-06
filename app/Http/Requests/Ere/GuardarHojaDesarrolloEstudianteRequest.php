<?php

namespace App\Http\Requests\Ere;

use App\Http\Requests\GeneralFormRequest;

class GuardarHojaDesarrolloEstudianteRequest extends GeneralFormRequest
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
            'archivo' => 'file|mimetypes:application/octet-stream,application/pdf,image/jpeg,image/png|max:10240',

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

    public function messages(): array
    {
        return [
            'archivo.mimetypes' => 'Solo se permiten imágenes y archivos PDF.',
            'archivo.file' => 'El archivo debe ser válido.',
            'archivo.max' => 'El archivo no debe superar los 10MB.',
        ];
    }
}
