<?php

namespace App\Services\asi;

use App\Models\asi\AsistenciaGeneral;

class AsistenciaGeneralService
{
    public static function obtenerCantidadRegistrosPorTipo($iEstudianteId, $iYAcadId, $iSedeId, $iTipoAsiId, $desde, $hasta)
    {
        return AsistenciaGeneral::selCantidadRegistrosPorTipo($iEstudianteId, $iYAcadId, $iSedeId, $iTipoAsiId, $desde, $hasta);
    }
}
