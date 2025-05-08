<?php

namespace App\Services\acad;

use Illuminate\Support\Facades\Storage;

class BuzonSugerenciasService
{
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
}
