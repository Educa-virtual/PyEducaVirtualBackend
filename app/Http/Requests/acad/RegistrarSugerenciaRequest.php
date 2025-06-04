<?php

namespace App\Http\Requests\acad;

use App\Http\Requests\GeneralFormRequest;

class RegistrarSugerenciaRequest extends GeneralFormRequest
{
    public function rules(): array
    {
        return [
            'cAsunto' => 'required|string|max:255',
            'cSugerencia' => 'required|string',
            'iPrioridadId' => 'required|integer',
            'fArchivos.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:30720'
        ];
    }

    public function attributes(): array
    {
        return [
            'cAsunto' => 'Asunto',
            'cSugerencia' => 'Sugerencia',
            'iPrioridadId' => 'Prioridad',
            'fArchivos.*' => 'Archivo'
        ];
    }
}

