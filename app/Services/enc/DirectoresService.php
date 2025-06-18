<?php

namespace App\Services\enc;

use App\Http\Requests\enc\ActualizarCategoriaRequest;
use App\Http\Requests\enc\RegistrarCategoriaRequest;
use App\Models\enc\Categoria;
use App\Models\enc\Director;
use Illuminate\Http\Request;

class DirectoresService
{
    public static function obtenerDirectoresParaFiltroEncuesta(Request $request)
    {
        $params = [
            'iUgelId' => $request->iUgelId,
            'iNivelTipoId' => $request->iNivelTipoId,
            'iIieeId' => $request->iIieeId,
            'iSedeId' => $request->iSedeId
        ];
        return Director::selDirectoresFiltroEncuesta($params);
    }
}
