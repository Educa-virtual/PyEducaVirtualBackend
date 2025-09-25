<?php

namespace App\Services\acad;

use App\Models\acad\FechaImportante;
use App\Models\acad\Matricula;

class FechasImportantesService
{
    public static function obtenerFechasImportantesCalendario($iSedeId, $iYAcadId)
    {
        $params = [
            $iSedeId,
            $iYAcadId
        ];
        return FechaImportante::selFechasImportantesCalendario($params);
    }

    public static function obtenerTiposFechasCalendario() {
        return FechaImportante::selTiposFechasCalendario();
    }
}
