<?php

namespace App\Http\Requests\grl;

use App\Http\Requests\GeneralFormRequest;

class ActualizarFotoPerfilRequest extends GeneralFormRequest
{
    public function rules(): array
    {
        return [
            'foto' => 'file|mimetypes:image/jpeg,image/png|max:10240',
        ];
    }

    public function attributes(): array
    {
        return [
            'foto' => 'Foto de perfil'
        ];
    }

    public function messages(): array
    {
        return [
            'foto.mimetypes' => 'Solo se permiten imágenes en formato JPG y PNG.',
            'foto.file' => 'El archivo debe ser válido.',
            'foto.max' => 'El archivo no debe superar los 10MB.',
        ];
    }
}
