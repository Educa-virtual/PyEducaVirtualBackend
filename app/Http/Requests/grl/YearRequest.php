<?php

namespace App\Http\Requests\grl;

use App\Http\Requests\GeneralFormRequest;

class YearRequest extends GeneralFormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'iYearId' => $this->route('iYearId') ?? $this->input('iYearId'),
            'iYAcadId' => $this->route('iYAcadId') ?? $this->input('iYAcadId'),
        ]);
    }

    public function rules(): array
    {
        return [
            'iYearId' => ['integer', 'nullable', 'required_without:iYAcadId'],
            'iYAcadId' => ['integer', 'nullable', 'required_without:iYearId'],
        ];
    }

    public function attributes(): array
    {
        return [
            'iYearId' => 'identificador del año',
            'iYAcadId' => 'identificador del año académico',
        ];
    }
}
