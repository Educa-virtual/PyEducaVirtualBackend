<?php

namespace App\Http\Requests\acad;

use App\Http\Requests\GeneralFormRequest;

class ListarCalendarioPeriodosRequest extends GeneralFormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'iYAcadId' => $this->route('iYAcadId') ?? $this->input('iYAcadId'),
            'iSedeId' => $this->route('iSedeId') ?? $this->input('iSedeId'),
            'iCalAcadId' => $this->route('iCalAcadId') ?? $this->input('iCalAcadId'),
        ]);
    }

    public function rules(): array
    {
        return [
            'iYAcadId' => ['integer', 'required'],
            'iSedeId' => ['integer', 'nullable'],
            'iCalAcadId' => ['integer', 'nullable'],
        ];
    }

    public function attributes(): array
    {
        return [
            'iYAcadId' => 'identificador del año académico',
            'iSedeId' => 'identificador del sede',
            'iCalAcadId' => 'identificador del calendario académico',
        ];
    }
}
