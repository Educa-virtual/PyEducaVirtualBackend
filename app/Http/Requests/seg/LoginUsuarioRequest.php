<?php

namespace App\Http\Requests\seg;

use App\Http\Requests\GeneralFormRequest;

class LoginUsuarioRequest extends GeneralFormRequest
{
    public function rules(): array
    {
        return [
            'user' => 'required',
            'pass' => 'required|string|min:6',
        ];
    }

    public function attributes(): array
    {
        return [
            'user' => 'Usuario',
            'pass' => 'Contraseña',
        ];
    }
}
