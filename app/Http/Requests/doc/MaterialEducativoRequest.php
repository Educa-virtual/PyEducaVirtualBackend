<?php

namespace App\Http\Requests\doc;

use App\Http\Requests\GeneralFormRequest;

class MaterialEducativoRequest extends GeneralFormRequest
{
    public function rules(): array
    {
        return [
            'iMatEducativoId' => 'nullable',
            'iDocenteId' => 'required',
            'idDocCursoId' => 'required',
            'iCredEntPerfId' => 'required',
            'iCursosNivelGradId' => 'required',
            'cMatEducativoTitulo' => 'required|string|max:600',
            'cMatEducativoDescripcion' => 'required|string|max:4000',
            'cMatEducativoUrl' => 'nullable',
        ];
    }
    public function messages(): array
    {
        return [
            'iDocenteId.required' => 'Es necesario el ID del docente',
            'idDocCursoId.required' => 'Es necesario el ID del curso',
            'iCredEntPerfId.required' => 'Es necesario el ID del perfil',
            'iCursosNivelGradId.required' => 'Es necesario el ID del nivel de grado',
            'cMatEducativoTitulo.required' => 'Es necesario el título',
            'cMatEducativoDescripcion.required' => 'Es necesario la descripción',
        ];
    }
}
