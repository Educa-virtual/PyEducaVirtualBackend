<?php

namespace App\Http\Requests\seg;

use App\Http\Requests\GeneralFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class SolicitarRegistroUsuarioRequest  extends GeneralFormRequest
{
    public function rules(): array
    {
        return [
            'cDocumento' => 'required',
            'cCodigoModular' => 'required',
            'cCargo' => 'required',
            'cCorreo' => 'required|email',
            'cNombres' => 'required',
            'cApellidos' => 'required'
        ];
    }

    public function attributes(): array
    {
        return [
            'cDocumento' => 'Documento',
            'cCodigoModular' => 'Código Modular',
            'cCargo' => 'Cargo',
            'cCorreo' => 'Correo electrónico',
            'cNombres' => 'Nombres',
            'cApellidos' => 'Apellidos'
        ];
    }
}
