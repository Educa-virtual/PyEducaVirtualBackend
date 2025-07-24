<?php

namespace App\Services\acad;

use App\Http\Requests\acad\RegistrarSugerenciaRequest;
use App\Models\acad\BuzonSugerencia;
use Exception;
use Illuminate\Support\Facades\Storage;

class BuzonSugerenciasEstudianteService
{
    public static function registrarSugerencia(RegistrarSugerenciaRequest $request)
    {
        $id = BuzonSugerencia::insBuzonSugerencias($request);
        self::guardarArchivos($id, $request->file('fArchivos'));
    }

    public static function obtenerSugerencias($request)
    {
        return BuzonSugerencia::selBuzonSugerenciasEstudiante($request);
    }

    public static function eliminarSugerencia($iSugerenciaId, $request)
    {
        return BuzonSugerencia::delBuzonSugerencias($iSugerenciaId, $request);
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
        $rutaArchivo = "sugerencias/" . $id . "/" . $archivo;
        if (!Storage::disk('public')->exists($rutaArchivo)) {
            throw new Exception('El archivo no existe');
        }
        $data = [];
        $data['contenido'] = Storage::disk('public')->get($rutaArchivo);
        $data['nombreArchivo'] = $archivo;
        return $data;
    }

    public static function obtenerArchivosSugerencia($iSugerenciaId)
    {
        $rutaCarpeta = "sugerencias/$iSugerenciaId/";
        if (!Storage::disk('public')->exists($rutaCarpeta)) {
            return []; // Retorna un array vacío si la carpeta no existe
        }

        $archivos = Storage::disk('public')->files($rutaCarpeta);
        $listaArchivos = [];

        foreach ($archivos as $archivo) {
            $listaArchivos[] = [
                'nombreArchivo' => basename($archivo),
                'rutaCompleta' => $archivo,
            ];
        }

        return $listaArchivos;
    }
}
