<?php

namespace App\Http\Requests\repo;

use App\Http\Requests\GeneralFormRequest;

class ActualizarCarpetaRequest extends GeneralFormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'iPersId' => $this->route('iPersId') ?? $this->input('iPersId'),
            'iCarpetaId' => $this->route('iCarpetaId') ?? $this->input('iCarpetaId'),
        ]);
    }

    public function rules(): array
    {
        return [
            'iCarpetaId' => ['integer', 'required'],
            'iPersId' => ['integer', 'required'],
            'iParentCarpetaId' => ['integer', 'nullable'],
            'cNombre' => ['required', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'iCarpetaId' => 'identificador de la carpeta',
            'iPersId' => 'identificador de la persona',
            'iParentCarpetaId' => 'carpeta padre',
            'cNombre' => 'nombre de la carpeta',
        ];
    }
}
