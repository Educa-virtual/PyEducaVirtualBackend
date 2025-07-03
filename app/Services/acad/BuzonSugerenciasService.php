<?php

namespace App\Services\acad;

use App\Http\Requests\acad\RegistrarSugerenciaRequest;
use App\Models\acad\BuzonSugerencia;
use Exception;
use Illuminate\Support\Facades\Storage;

class BuzonSugerenciasService
{
    public static function registrarSugerencia(RegistrarSugerenciaRequest $request) {
        $id=BuzonSugerencia::insBuzonSugerencias($request);
        self::guardarArchivos($id, $request->file('fArchivos'));
    }

    public static function obtenerSugerenciasEstudiante($request) {
        return BuzonSugerencia::selBuzonSugerenciasEstudiante($request);
    }

    public static function eliminarSugerencia($iSugerenciaId, $request) {
        return BuzonSugerencia::delBuzonSugerenciasEstudiante($iSugerenciaId, $request);
    }

    public static function guardarArchivos($id, $archivos)
    {
        if ($archivos) {
            $rutaDestino = 'sugerencias/' . $id;
            if (!is_array($archivos)) {
                //Se convierte en array para poder usar el foreach
                $archivos = [$archivos];
            }
            foreach ($archivos as $archivo) {
                $nombreArchivo = $archivo->getClientOriginalName();
                if (!Storage::disk('public')->exists($rutaDestino)) {
                    Storage::disk('public')->makeDirectory($rutaDestino);
                }
                $archivo->storeAs($rutaDestino, $nombreArchivo, 'public');
            }
        }
    }

    public static function descargarArchivo($id, $archivo)
    {
        /*
        $rutaCarpeta = "sugerencias/$iSugerenciaId/";
            $rutaArchivo = $rutaCarpeta . $nombreArchivo;

            if (!Storage::disk('public')->exists($rutaArchivo)) {
                return FormatearMensajeHelper::error(new Exception('El archivo no existe'), Response::HTTP_NOT_FOUND);
            }

            return Storage::disk('public')->download($rutaArchivo, $nombreArchivo);
        */
        $rutaArchivo = "sugerencias/".$id."/".$archivo;
        if (!Storage::disk('public')->exists($rutaArchivo)) {
            throw new Exception('El archivo no existe');
        }
        $data = [];
        $data['contenido'] = Storage::disk('public')->get($rutaArchivo);
        $data['nombreArchivo'] = $archivo;/*ucwords(strtolower($area->cCursoNombre)) . '-' . $area->cGradoAbreviacion . '-'
            . str_replace('Educación ', '', $area->cNivelTipoNombre) . '-' . $fechaInicio->year . '.pdf';*/
        return $data;
    }
}
