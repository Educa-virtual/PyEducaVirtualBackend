<?php

namespace App\Http\Requests\acad;

use App\Http\Requests\GeneralFormRequest;

class ActualizarDistribucionBloqueRequest extends GeneralFormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'iDistribucionBloqueId' => $this->route('iDistribucionBloqueId') ?? $this->input('iDistribucionBloqueId'),
        ]);
    }

    public function rules(): array
    {
        return [
            'iDistribucionBloqueId' => ['integer', 'required'],
            'iTipoDistribucionId' => ['integer', 'required'],
            'dtInicioBloque' => ['date', 'required'],
            'dtFinBloque' => ['date', 'required'],
        ];
    }

    public function attributes(): array
    {
        return [
            'iDistribucionBloqueId' => 'identificador del bloque de distribución',
            'iTipoDistribucionId' => 'tipo de distribución',
            'dtInicioBloque' => 'fecha de inicio',
            'dtFinBloque' => 'fecha de fin',
        ];
    }
}
