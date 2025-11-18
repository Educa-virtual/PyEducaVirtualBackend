<?php

namespace App\Http\Requests\enc;

use App\Http\Requests\GeneralFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class GuardarCategoriaRequest extends GeneralFormRequest
{
    public function rules(): array
    {
        return [
            'cCateNombre' => 'required|string|max:150',
            'cCateDescripcion' => 'nullable|string|max:500',
            'cCateImagenNombre' => 'nullable|string|max:150',
            'bCatePermisoDremo' => 'nullable|boolean',
            'bCatePermisoUgel' => 'nullable|boolean',
            'bCatePermisoDirector' => 'nullable|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'cCateNombre' => 'Nombre',
            'cCateDescripcion' => 'Descripción',
            'cCateImagenNombre' => 'Imagen',
            'bCatePermisoDremo' =>'Permiso para DREMO',
            'bCatePermisoUgel' => 'Permiso para UGEL',
            'bCatePermisoDirector' => 'Permiso para Director',
        ];
    }
}
