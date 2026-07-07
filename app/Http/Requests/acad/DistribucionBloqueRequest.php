<?php

namespace App\Http\Requests\acad;

use App\Http\Requests\GeneralFormRequest;

class DistribucionBloqueRequest extends GeneralFormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'iDistribucionBloqueId' => $this->route('iDistribucionBloqueId') ?? $this->input('iDistribucionBloqueId'),
        ]);
    }

    public function rules(): array
    {
        return [
            'iDistribucionBloqueId' => ['integer', 'required'],
        ];
    }

    public function attributes(): array
    {
        return [
            'iDistribucionBloqueId' => 'identificador del bloque de distribución',
        ];
    }
}
