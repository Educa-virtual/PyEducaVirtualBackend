<?php

namespace App\Http\Requests\grl;

use App\Http\Requests\GeneralFormRequest;

class GuardarFeriadoNacionalMasivoRequest extends GeneralFormRequest
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
            'iYAcadId' => 'integer|required',
            'jsonFeriadosNacionales' => 'required|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'iYAcadId' => 'año académico',
            'jsonFeriadosNacionales' => 'feriados importados',
        ];
    }
}
