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
        ]);
    }

    public function rules(): array
    {
        return [
            'iYearId' => ['integer', 'required'],
        ];
    }

    public function attributes(): array
    {
        return [
            'iYearId' => 'identificador del año',
        ];
    }
}
