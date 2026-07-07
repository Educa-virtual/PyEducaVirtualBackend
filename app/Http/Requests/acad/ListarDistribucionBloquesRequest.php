<?php

namespace App\Http\Requests\acad;

use App\Http\Requests\GeneralFormRequest;

class ListarDistribucionBloquesRequest extends GeneralFormRequest
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
            'iYAcadId' => ['integer', 'required'],
        ];
    }

    public function attributes(): array
    {
        return [
            'iYAcadId' => 'identificador del año académico',
        ];
    }
}
