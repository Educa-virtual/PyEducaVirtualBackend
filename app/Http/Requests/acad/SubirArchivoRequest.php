<?php

namespace App\Http\Requests\acad;

use App\Http\Requests\GeneralFormRequest;

class SubirArchivoRequest extends GeneralFormRequest
{
    
    public function rules(): array
    {
        return [
            'documento' => 'required|file|mimes:pdf|max:10240',
            'dremoYear' => 'required|string',
            'cIieeCodigoModular' => 'required|string',
            'iPersId' => 'required|string',
        ];
    }
    public function messages(): array
    {
        return [
            'documento.required' => 'Es necesario que cargue un documento en Formato PDF.',
            'documento.file' => 'El archivo debe ser válido.',
            'documento.mimes' => 'El archivo debe ser un PDF.',
            'documento.max' => 'El archivo no debe superar los 10MB.',

            'dremoYear.required' => 'El año es obligatorio.',

            'cIieeCodigoModular.required' => 'El código modular es obligatorio.',

            'iPersId.required' => 'El ID de la persona es obligatorio.',
        ];
    }
}
