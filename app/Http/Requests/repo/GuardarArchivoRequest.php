<?php

namespace App\Http\Requests\repo;

use App\Http\Requests\GeneralFormRequest;

class GuardarArchivoRequest extends GeneralFormRequest
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
        ]);
    }

    public function rules(): array
    {
        return [
            'iCarpetaId' => ['integer', 'required'],
            'iPersId' => ['integer', 'required'],
            'archivo' => ['required', 'file', 'max:5120', 'mimes:pdf,doc,docx,xlsx,xls,ppt,pptx,txt,csv,jpg,jpeg,png,gif,mp4,zip,rar'],
        ];
    }

    public function attributes(): array
    {
        return [
            'iCarpetaId' => 'identificador de la carpeta',
            'iPersId' => 'identificador de la persona',
            'archivo' => 'archivo',
        ];
    }
}
