<?php

namespace App\Services\enc;

use App\Http\Requests\enc\RegistrarCategoriaRequest;
use App\Models\enc\Categoria;

class CategoriasService
{
    public static function obtenerCategorias()
    {
        return Categoria::selCategorias();
    }

    public static function obtenerCategoriasTotalEncuestas() {
        return Categoria::selCategoriasTotalEncuestas();
    }

    public static function registrarCategoria(RegistrarCategoriaRequest $request)
    {
        $params = [
            $request->cNombre,
            $request->cDescripcion
        ];
        return Categoria::insCategorias($params);
    }
}
