<?php

namespace App\Services\enc;

use App\Models\enc\Docente;
use Illuminate\Http\Request;

class DocentesService
{
    public static function obtenerDocentesParaFiltroEncuesta(Request $request)
    {
        $params = [
            'iYAcadId' => $request->iYAcadId,
            'iUgelId' => $request->iUgelId,
            'iNivelTipoId' => $request->iNivelTipoId,
            'iIieeId' => $request->iIieeId,
            'iSedeId' => $request->iSedeId,
        ];

        return Docente::selDocentesFiltroEncuesta($params);
    }
}
