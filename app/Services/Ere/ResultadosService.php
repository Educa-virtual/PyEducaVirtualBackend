<?php

namespace App\Services\Ere;

use App\Http\Requests\Ere\GuardarHojaDesarrolloEstudianteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResultadosService
{
    public static function existeHojaDesarrolloEstudiante($iEvaluacionId, $iCursosNivelGradId, $iEstudianteId)
    {
        $rutaArchivo = "ere/hoja-desarrollo/$iEvaluacionId/areas/$iCursosNivelGradId/estudiante/$iEstudianteId";
        $archivos = Storage::disk('public')->files($rutaArchivo);
        return !empty($archivos);
    }

    public static function guardarHojaDesarrolloEstudiante(GuardarHojaDesarrolloEstudianteRequest $request)
    {
        $archivo = $request->file('archivo');
        $nombreArchivo = 'hoja-desarrollo.pdf';
        $rutaDestino = "ere/hoja-desarrollo/$request->iEvaluacionId/areas/$request->iCursosNivelGradId/estudiante/$request->iEstudianteId";

        if (!Storage::disk('public')->exists($rutaDestino)) {
            Storage::disk('public')->makeDirectory($rutaDestino);
        }
        $archivo->move(Storage::disk('public')->path($rutaDestino), $nombreArchivo);
    }
}
