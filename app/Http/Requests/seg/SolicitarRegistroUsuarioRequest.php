<?php

namespace App\Http\Requests\seg;

use App\Http\Requests\GeneralFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class SolicitarRegistroUsuarioRequest  extends GeneralFormRequest
{
    public function rules(): array
    {
        return [
            'documento' => 'required',
            'nombres' => 'required',
            'nombres' => 'required',
            'codigoModular' => 'required',
            'cargo' => 'required',
            'correo' => 'required|email'
        ];
    }

    public function attributes(): array
    {
        return [
            'documento' => 'Documento',
            'nombre' => 'Nombre',
            'codigoModular' => 'Código Modular',
            'cargo' => 'Cargo',
            'correo' => 'Correo electrónico',
        ];
    }
}
