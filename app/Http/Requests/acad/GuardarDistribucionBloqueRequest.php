<?php

namespace App\Http\Requests\acad;

use App\Http\Requests\GeneralFormRequest;

class GuardarDistribucionBloqueRequest extends GeneralFormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'iYAcadId' => $this->route('iYAcadId') ?? $this->input('iYAcadId'),
        ]);
    }

    public function rules(): array
    {
        return [
            'iYAcadId' => ['integer', 'required'],
            'iTipoDistribucionId' => ['integer', 'required'],
            'dtInicioBloque' => ['date', 'required'],
            'dtFinBloque' => ['date', 'required'],
        ];
    }

    public function attributes(): array
    {
        return [
            'iYAcadId' => 'identificador del año',
            'iTipoDistribucionId' => 'tipo de distribución',
            'dtInicioBloque' => 'fecha de inicio',
            'dtFinBloque' => 'fecha de fin',
        ];
    }
}
