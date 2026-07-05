<?php

namespace App\Http\Requests\grl;

use App\Http\Requests\GeneralFormRequest;

class BorrarFeriadoNacionalRequest extends GeneralFormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'iFeriadoId' => $this->route('iFeriadoId') ?? $this->input('iFeriadoId'),
        ]);
    }

    public function rules(): array
    {
        return [
            'iFeriadoId' => ['integer', 'required'],
        ];
    }

    public function attributes(): array
    {
        return [
            'iFeriadoId' => 'feriado nacional',
        ];
    }
}
