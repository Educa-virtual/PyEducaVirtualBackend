<?php

namespace App\Http\Requests\repo;

use App\Http\Requests\GeneralFormRequest;

class GuardarCarpetaRequest extends GeneralFormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'iPersId' => $this->route('iPersId') ?? $this->input('iPersId'),
        ]);
    }

    public function rules(): array
    {
        return [
            'iParentCarpetaId' => ['integer', 'nullable'],
            'iPersId' => ['integer', 'required'],
            'cNombre' => ['required', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'iParentCarpetaId' => 'carpeta padre',
            'iPersId' => 'identificador de la persona',
            'cNombre' => 'nombre de la carpeta',
        ];
    }
}
