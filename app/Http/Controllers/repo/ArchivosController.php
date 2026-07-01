<?php

namespace App\Http\Controllers\repo;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Http\Requests\repo\GuardarArchivoRequest;
use App\Models\repo\Archivo;
use App\Models\repo\Carpeta;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ArchivosController extends Controller
{
    public function guardarArchivo(GuardarArchivoRequest $request)
    {
        try {
            $file = $request->file('archivo');
            if (!$file or !$file->isValid()) {
                throw new Exception('El archivo no es válido', 400);
            }
            $ruta_persona = 'repositorio/' . $request->iPersId;
            $nombre_extension = hash('sha256', uniqid()) . '.' . $file->getClientOriginalExtension();
            $request->merge([
                'cNombreOriginal' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'cExtension' => $file->getClientOriginalExtension(),
                'iCarpetaId' => $request->iCarpetaId,
                'cNombre' => $nombre_extension,
                'iTamano' => $file->getSize(),
            ]);
            $data = Archivo::insArchivos($request);

            if ($data->iArchivoId > 0) {
                $this->subirArchivo($file, $ruta_persona, $nombre_extension);
                return FormatearMensajeHelper::ok('Se ha guardado exitosamente ', $data);
            } else {
                throw new Exception('No se ha podido guardar', 500);
            }
        } catch (\Exception $e) {
            if(file_exists($request->cRuta . '/' . $request->cNombreOriginal)) {
                Storage::disk('local')->delete($request->cRuta . '/' . $request->cNombreOriginal);
            }
            return FormatearMensajeHelper::error($e);
        }
    }

    public function descargarArchivo(Request $request)
    {
        try {
            $archivo = Archivo::selArchivo($request);
            if (empty($archivo)) {
                throw new Exception('No se encontró el archivo', 404);
            }
            $path = 'repositorio/' . $archivo->iPersId . '/' . $archivo->cRuta;
            if (!Storage::exists($path)) {
                throw new Exception('El archivo no existe en el servidor', 404);
            }

            $contenido = base64_encode(Storage::get($path));
            $mime = Storage::mimeType($path);
            $nombre = $archivo->cNombre . '.' . $archivo->cExtension;

            return FormatearMensajeHelper::ok('Se ha obtenido exitosamente ', [
                'nombre' => $nombre,
                'mime' => $mime,
                'base64' => $contenido,
            ]);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function eliminarArchivo(Request $request)
    {
        try {
            $archivo = Archivo::selArchivo($request);
            if (!$archivo) {
                throw new Exception('No se encontró el archivo', 404);
            }

            $data = Archivo::delArchivos($request);
            if ($data->iArchivoId > 0) {
                if (Storage::exists($archivo->cRuta)) {
                    Storage::delete($archivo->cRuta);
                }
                return FormatearMensajeHelper::ok('Se ha eliminado exitosamente ', $data);
            } else {
                throw new Exception('No se ha podido eliminar', 500);
            }
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    private function subirArchivo($archivo, $ruta, $nombre_archivo)
    {
        if(!Storage::disk('local')->exists($ruta)) {
            Storage::disk('local')->makeDirectory($ruta, 0755, true);
        }
        $archivo->move(Storage::disk('local')->path($ruta), $nombre_archivo);
        if (Storage::disk('local')->exists($ruta . '/' . $nombre_archivo)) {
            return $nombre_archivo;
        }
        return null;
    }
}
