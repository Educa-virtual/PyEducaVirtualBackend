<?php

namespace App\Services\Ere;

use Illuminate\Support\Facades\Storage;

class ResultadosService
{
    public static function existeHojaDesarrolloEstudiante($iEvaluacionId, $iCursosNivelGradId, $iEstudianteId)
    {
        $rutaArchivo = "ere/hoja-desarrollo/$iEvaluacionId/areas/$iCursosNivelGradId/estudiante/$iEstudianteId/hoja-desarrollo.pdf";
        return Storage::disk('public')->exists($rutaArchivo);
    }
}
