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
            'cNombre' => $request->cNombre,
            'cDescripcion' => $request->cDescripcion,
            'bPuedeCrearEspDremo' => $request->bPuedeCrearEspDremo,
            'bPuedeCrearAccesoEspUgel' => $request->bPuedeCrearAccesoEspUgel,
            'bPuedeCrearDirector' => $request->bPuedeCrearDirector,
            'cImagenUrl' => $request->cImagenUrl
        ];
        return Categoria::insCategorias($params);
    }

    public static function actualizarCategoria(ActualizarCategoriaRequest $request)
    {
        $params = [
            'iCategoriaEncuestaId' => $request->iCategoriaEncuestaId,
            'cNombre' => $request->cNombre,
            'cDescripcion' => $request->cDescripcion,
            'bPuedeCrearEspDremo' => $request->bPuedeCrearEspDremo,
            'bPuedeCrearAccesoEspUgel' => $request->bPuedeCrearAccesoEspUgel,
            'bPuedeCrearDirector' => $request->bPuedeCrearDirector,
            'cImagenUrl' => $request->cImagenUrl
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
