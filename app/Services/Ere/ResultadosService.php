<?php

namespace App\Services\Ere;

use App\Http\Requests\Ere\GuardarHojaDesarrolloEstudianteRequest;
use App\Http\Requests\Ere\HojaDesarrolloEstudianteRequest;
use Exception;
use Illuminate\Support\Facades\Storage;

class ResultadosService
{
    private static function obtenerRutaHojaDesarrolloEstudiante($iEvaluacionId, $iCursosNivelGradId, $iEstudianteId)
    {
        return "ere/hoja-desarrollo/$iEvaluacionId/areas/$iCursosNivelGradId/estudiante/$iEstudianteId";
    }

    public static function existeHojaDesarrolloEstudiante($iEvaluacionId, $iCursosNivelGradId, $iEstudianteId)
    {
        $rutaArchivo = self::obtenerRutaHojaDesarrolloEstudiante($iEvaluacionId, $iCursosNivelGradId, $iEstudianteId);
        $archivos = Storage::disk('public')->files($rutaArchivo);
        return !empty($archivos);
    }

    public static function guardarHojaDesarrolloEstudiante(GuardarHojaDesarrolloEstudianteRequest $request)
    {
        $archivo = $request->file('archivo');
        $nombreArchivo = 'hoja-desarrollo.pdf';
        $rutaDestino = self::obtenerRutaHojaDesarrolloEstudiante($request->iEvaluacionId, $request->iCursosNivelGradId, $request->iEstudianteId);

        if (!Storage::disk('public')->exists($rutaDestino)) {
            Storage::disk('public')->makeDirectory($rutaDestino);
        }
        $archivo->move(Storage::disk('public')->path($rutaDestino), $nombreArchivo);
    }

    public static function obtenerHojaDesarrolloEstudiante(HojaDesarrolloEstudianteRequest $request)
    {
        $rutaDirectorio = self::obtenerRutaHojaDesarrolloEstudiante($request->iEvaluacionId, $request->iCursosNivelGradId, $request->iEstudianteId);
        $archivos = Storage::disk('public')->files($rutaDirectorio);
        if (empty($archivos)) {
            throw new Exception('El archivo no existe');
        }
        return $archivos[0];
    }

    public static function eliminarHojaDesarrolloEstudiante(HojaDesarrolloEstudianteRequest $request)
    {
        $rutaDirectorio = self::obtenerRutaHojaDesarrolloEstudiante($request->iEvaluacionId, $request->iCursosNivelGradId, $request->iEstudianteId);
        $archivos = Storage::disk('public')->files($rutaDirectorio);
        if (empty($archivos)) {
            throw new Exception('El archivo no existe');
        }
        foreach ($archivos as $archivo) {
            Storage::disk('public')->delete($archivo);
        }
    }
}
