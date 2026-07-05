<?php

namespace App\Http\Requests\grl;

use App\Http\Requests\GeneralFormRequest;

class GuardarFeriadoNacionalRequest extends GeneralFormRequest
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
            'cFeriadoNombre' => 'required|string|max:200',
            'dtFeriado' => 'required|data',
            'bFeriadoEsRecuperable' => 'required|boolean',
            'cFeriadoDescripcion' => 'nullable|string',
            'cDocumento' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'iYAcadId' => 'año académico',
        ];
    }
}
