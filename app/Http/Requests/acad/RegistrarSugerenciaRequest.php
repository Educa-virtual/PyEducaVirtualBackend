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
            'fArchivos.*' => 'file|mimetypes:application/octet-stream,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,pdf,image/jpeg,image/png|max:10240',

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

