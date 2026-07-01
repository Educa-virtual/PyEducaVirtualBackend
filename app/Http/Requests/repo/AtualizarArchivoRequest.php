<?php

namespace App\Http\Requests\repo;

use App\Http\Requests\GeneralFormRequest;

class ActualizarArchivoRequest extends GeneralFormRequest
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
            'iArchivoId' => $this->route('iArchivoId') ?? $this->input('iArchivoId'),
        ]);
    }

    public function rules(): array
    {
        return [
            'iCarpetaId' => ['integer', 'required'],
            'iPersId' => ['integer', 'required'],
            'iArchivoId' => ['integer', 'required'],
            'cNombre' => ['required', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'iCarpetaId' => 'identificador de la carpeta',
            'iPersId' => 'identificador de la persona',
            'iArchivoId' => 'identificador del archivo',
            'cNombre' => 'nombre del archivo',
        ];
    }
}
