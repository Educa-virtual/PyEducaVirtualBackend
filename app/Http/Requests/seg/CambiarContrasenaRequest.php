<?php

namespace App\Http\Requests\seg;

use App\Http\Requests\GeneralFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class CambiarContrasenaRequest extends GeneralFormRequest
{
    public function rules(): array
    {
        return [
            'contrasenaNueva' => [
                'nullable',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ]
        ];
    }
}
