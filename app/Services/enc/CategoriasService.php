<?php

namespace App\Services\enc;

use App\Helpers\VerifyHash;
use App\Http\Requests\enc\ActualizarCategoriaRequest;
use App\Http\Requests\enc\RegistrarCategoriaRequest;
use App\Models\enc\Categoria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriasService
{
    public static function obtenerCategorias()
    {
        $categorias= Categoria::selCategorias();
        foreach ($categorias as $categoria) {
            $categoria->iCategoriaEncuestaId = VerifyHash::encodexId($categoria->iCategoriaEncuestaId);
        }
        return $categorias;
    }

    public static function obtenerDetallesCategoria($iCategoriaEncuestaId)
    {
        $id = VerifyHash::decodesxId($iCategoriaEncuestaId);
        $categoria = DB::selectOne('SELECT * FROM enc.categoria_encuesta WHERE iCategoriaEncuestaId = ?', [$id]);
        if (!$categoria) {
            throw new Exception('Categoría no encontrada');
        }
        $categoria->iCategoriaEncuestaId = $iCategoriaEncuestaId;
        return $categoria;
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
