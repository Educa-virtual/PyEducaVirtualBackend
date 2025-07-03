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
            'cDescripcion' => 'nullable|string|max:150',
            'bPuedeCrearEspDremo' =>'nullable|boolean',
            'bPuedeCrearEspUgel'=>'nullable|boolean',
            'bPuedeCrearDirector'=>'nullable|boolean',
            'cImagenUrl' => 'nullable|string|max:255'
        ];
    }

    public function attributes(): array
    {
        return [
            'cNombre' => 'Nombre',
            'cDescripcion' => 'Descripción',
            'bPuedeCrearEspDremo' =>'Esp. Dremo puede crear encuestas',
            'bPuedeCrearEspUgel' => 'Esp. Ugel puede crear encuestas',
            'bPuedeCrearDirector' => 'Director puede crear encuestas',
            'cImagenUrl' => 'URL de la imagen'
        ];
    }
}
