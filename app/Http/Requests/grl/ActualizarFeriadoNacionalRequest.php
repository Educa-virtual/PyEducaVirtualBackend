<?php

namespace App\Http\Requests\grl;

use App\Http\Requests\GeneralFormRequest;

class ActualizarFeriadoNacionalRequest extends GeneralFormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'iFeriadoId' => $this->route('iFeriadoId') ?? $this->input('iFeriadoId'),
        ]);
    }

    public function rules(): array
    {
        return [
            'iFeriadoId' => 'integer|required',
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
            'iFeriadoId' => 'año académico',
        ];
    }
}
