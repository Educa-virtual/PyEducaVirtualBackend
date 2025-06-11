<?php

namespace App\Http\Requests\enc;

use App\Http\Requests\GeneralFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class RegistrarCategoriaRequest extends GeneralFormRequest
{
    public function rules(): array
    {
        return [
            'cNombre' => 'required|string|max:150',
            'cDescripcion' => 'required|string|max:150'
        ];
    }

    public function attributes(): array
    {
        return [
            'cNombre' => 'Nombre',
            'cDescripcion' => 'Descripción'
        ];
    }
}
