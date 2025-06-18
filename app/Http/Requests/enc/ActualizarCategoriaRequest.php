<?php

namespace App\Http\Requests\enc;

use App\Http\Requests\GeneralFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class ActualizarCategoriaRequest extends GeneralFormRequest
{
    public function rules(): array
    {
        return [
            'iCategoriaEncuestaId' => 'required|integer|exists:enc.categoria_encuesta,iCategoriaEncuestaId',
            'cNombre' => 'required|string|max:150',
            'cDescripcion' => 'required|string|max:150',
            'bPuedeCrearEspDremo' =>'required|boolean',
            'bPuedeCrearAccesoEspUgel'=>'required|boolean',
            'bPuedeCrearDirector'=>'required|boolean',
            'cImagenUrl' => 'nullable|string|max:255'
        ];
    }

    public function attributes(): array
    {
        return [
            'iCategoriaEncuestaId' => 'ID de la categoría de encuesta',
            'cNombre' => 'Nombre',
            'cDescripcion' => 'Descripción',
            'bPuedeCrearEspDremo' =>'Esp. Dremo puede crear encuestas',
            'bPuedeCrearAccesoEspUgel' => 'Esp. Ugel puede crear encuestas',
            'bPuedeCrearDirector' => 'Director puede crear encuestas',
            'cImagenUrl' => 'URL de la imagen'
        ];
    }
}
