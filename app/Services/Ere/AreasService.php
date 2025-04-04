<?php

namespace App\Services\ere;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class AreasService
{
    /*public static function tieneArchivoErePdfSubido($iEvaluacionid, $iCursosNivelGradId)
    {
        return file_exists(public_path("ere/evaluaciones/$iEvaluacionid/areas/$iCursosNivelGradId/examen.pdf"));
    }*/

    public static function tieneArchivoErePdfSubido($iEvaluacionId, $iCursosNivelGradId)
    {
        $rutaArchivo = "ere/evaluaciones/$iEvaluacionId/areas/$iCursosNivelGradId/examen.pdf";
        return Storage::disk('public')->exists($rutaArchivo);
    }

    public static function guardarArchivoErePdf($request, $evaluacionId, $areaId)
    {
        $archivo = $request->file('archivo');
        $nombreArchivo = 'examen.pdf';
        $rutaDestino = "ere/evaluaciones/$evaluacionId/areas/$areaId";

        if (!Storage::disk('public')->exists($rutaDestino)) {
            Storage::disk('public')->makeDirectory($rutaDestino);
        }
        Storage::disk('public')->putFileAs($rutaDestino, $archivo, $nombreArchivo);
    }

    public static function obtenerArchivoErePdf($evaluacion, $area)
    {
        $fechaInicio = new Carbon($evaluacion->dtEvaluacionFechaInicio);
        $rutaArchivo = "ere/evaluaciones/$evaluacion->evaluacionIdHashed/areas/$area->areaIdCifrado/examen.pdf";
        if (!Storage::disk('public')->exists($rutaArchivo)) {
            throw new Exception('El archivo no existe');
        }
        $data = [];
        $data['contenido'] = Storage::disk('public')->get($rutaArchivo);
        $data['nombreArchivo'] = ucwords(strtolower($area->cCursoNombre)) . '-' . $area->cGradoAbreviacion . '-'
            . str_replace('Educación ', '', $area->cNivelTipoNombre) . '-' . $fechaInicio->year . '.pdf';
        return $data;
    }
}
