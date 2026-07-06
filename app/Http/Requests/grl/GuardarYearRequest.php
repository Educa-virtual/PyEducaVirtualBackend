<?php

namespace App\Http\Requests\grl;

use App\Http\Requests\GeneralFormRequest;

class GuardarYearRequest extends GeneralFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
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
            'cYearNombre' => 'nombre',
            'cYearOficial' => 'nombre oficial del año',
            'iYearEstado' => 'estado',
            'dtYAcadInicio' => 'fecha de inicio',
            'dYAcadFin' => 'fecha de fin',
        ];
    }
}
