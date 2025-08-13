<?php

namespace App\Services\enc;

use App\Http\Requests\enc\RegistrarCategoriaRequest;
use App\Models\enc\Categoria;
use App\Models\enc\Docente;
use App\Models\enc\Encuesta;
use App\Models\enc\Estudiante;
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
            'iSedeId' => $request->iSedeId
        ];
        return Docente::selDocentesFiltroEncuesta($params);
    }
}
