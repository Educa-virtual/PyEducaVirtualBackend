<?php

namespace App\Http\Requests\seg;

use App\Http\Requests\GeneralFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class CambiarClaveRequest extends GeneralFormRequest
{
    public function rules(): array
    {
        return [
            'contraseniaNueva' => [
                'nullable',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ],
            [
                'contraseniaNueva.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'contraseniaNueva.regex' => 'La contraseña debe contener una mayúscula, una minúscula y un número.'
            ]
        ];
    }
}
