<?php

namespace App\Http\Requests\enc;

use App\Http\Requests\GeneralFormRequest;

class RegistrarConfiguracionEncuestaRequest extends GeneralFormRequest
{
    public function rules(): array
    {
        return [
            'iConfEncId' => 'required|numeric',
            'cConfEncNombre' => 'required|string',
            'cConfEncDesc'=> 'required|string',
            'iTiemDurId' => 'required|numeric',
            'iCategoriaEncuestaId' => 'required|string',
            'dConfEncFin' => 'required',
            'dConfEncInicio' => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'iConfEncId' => 'ID de la configuracion de encuesta',
            'cConfEncNombre' => 'Nombre',
            'cConfEncDesc' => 'Descripción',
            'iTiemDurId' => 'Tiempo de duracion',
            'iCategoriaEncuestaId' => 'ID de categoría de encuesta',
            'dConfEncFin' => 'Fecha fin',
            'dConfEncInicio' => 'Fecha de inicio'
        ];
    }
}
