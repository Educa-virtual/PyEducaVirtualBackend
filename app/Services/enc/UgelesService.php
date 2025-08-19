<?php

namespace App\Services\enc;

use App\Http\Requests\enc\RegistrarCategoriaRequest;
use App\Models\enc\Categoria;
use App\Models\enc\Encuesta;
use App\Models\enc\Estudiante;
use App\Models\enc\Ugel;
use Illuminate\Http\Request;

class UgelesService
{
    public static function obtenerUgelesParaFiltroEncuesta(Request $request)
    {
        $params = [
            'iUgelId' => $request->iUgelId
        ];
        return Ugel::selUgelesFiltroEncuesta($params);
    }
}
