<?php

namespace App\Services\enc;

use App\Http\Requests\enc\ActualizarCategoriaRequest;
use App\Http\Requests\enc\RegistrarCategoriaRequest;
use App\Models\enc\Categoria;
use Illuminate\Http\Request;

class CategoriasService
{
    public static function obtenerCategorias()
    {
        return Categoria::selCategorias();
    }

    public static function registrarCategoria(RegistrarCategoriaRequest $request)
    {
        $params = [
            $request->cNombre,
            $request->cDescripcion,
            $request->bPuedeCrearEspDremo ?? false,
            $request->bPuedeCrearEspUgel ?? false,
            $request->bPuedeCrearDirector ?? false,
            $request->cImagenUrl
        ];
        return Categoria::insCategoria($params);
    }

    public static function actualizarCategoria(ActualizarCategoriaRequest $request)
    {
        $params = [
            $request->iCategoriaEncuestaId,
            $request->cNombre,
            $request->cDescripcion,
            $request->bPuedeCrearEspDremo,
            $request->bPuedeCrearEspUgel,
            $request->bPuedeCrearDirector,
            $request->cImagenUrl
        ];
        return Categoria::updCategorias($params);
    }

    public static function eliminarCategoria(Request $request)
    {
        $validated = $request->validate([
            'iCategoriaEncuestaId' => 'required|integer',
        ]);

        $params = [
            'iCategoriaEncuestaId' => $validated['iCategoriaEncuestaId']
        ];
        return Categoria::delCategoria($params);
    }
}
