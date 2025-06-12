<?php

namespace App\Http\Requests\seg;

use App\Http\Requests\GeneralFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class CambiarContrasenaRequest extends GeneralFormRequest
{
    public function rules(): array
    {
        /*
            Mínimo 8 caracteres
            Al menos una letra mayúscula
            Al menos una letra minúscula
            Al menos un número
            Sin requerir caracteres especiales
        */
        return [
            'contrasenaNueva' => [
                'nullable',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'contrasenaNueva.regex' => 'La contraseña debe tener al menos 8 caracteres, una letra mayúscula, una minúscula y un número.',
        ];
    }
}
