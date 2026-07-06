<?php

namespace App\Http\Requests\grl;

use App\Http\Requests\GeneralFormRequest;

class ActualizarYearRequest extends GeneralFormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'iYearId' => $this->route('iYearId') ?? $this->input('iYearId'),
        ]);
    }

    public function rules(): array
    {
        return [
            'iYearId' => ['integer', 'required'],
            'cYearNombre' => ['required', 'string', 'max:4'],
            'cYearOficial' => ['required', 'string', 'max:512'],
            'iYearEstado' => ['integer', 'required'],
            'dtYAcadInicio' => ['date', 'required'],
            'dYAcadFin' => ['date', 'required'],
        ];
    }

    public function attributes(): array
    {
        return [
            'iYearId' => 'identificador del año',
            'cYearNombre' => 'nombre',
            'cYearOficial' => 'nombre oficial del año',
            'iYearEstado' => 'estado',
            'dtYAcadInicio' => 'fecha de inicio',
            'dYAcadFin' => 'fecha de fin',
        ];
    }
}
