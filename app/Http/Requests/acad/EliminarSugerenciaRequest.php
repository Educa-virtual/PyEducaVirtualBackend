<?php

namespace App\Http\Requests\acad;

use App\Http\Requests\GeneralFormRequest;

class EliminarSugerenciaRequest extends GeneralFormRequest
{
    public function rules(): array
    {
        return [
            'iSugerenciaId' => 'required|integer'
        ];
    }

    public function attributes(): array
    {
        return [
            'iSugerenciaId' => 'ID de la sugerencia'
        ];
    }
}

