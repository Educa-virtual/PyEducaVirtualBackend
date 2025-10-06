<?php

namespace App\Http\Controllers\repo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ArchivosController extends Controller
{
    public function guardarArchivo(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'iCarpetaId' => ['required'],
            'iPersId' => ['required'],
            'archivo' => ['required', 'file', 'max:5120'],
        ], [
            'iCarpetaId.required' => 'No se encontró el identificador de la carpeta',
            'iPersId.required' => 'No se encontró el identificador de la persona',
            'archivo.required' => 'No se ha seleccionado ningún archivo',
            'archivo.file' => 'El archivo debe ser un archivo válido',
            'archivo.max' => 'El tamaño máximo del archivo es 5MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $file = $request->file('archivo');
        $nombreOriginal = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $size = $file->getSize();

        $size = $this->formatSizeUnits($size);

        $ruta = $file->store("repositorio/{$request->iPersId}/{$request->iCarpetaId}");

        try {
            $fieldsToDecode = [
                'iArchivoId',
                'iCarpetaId',
                'iPersId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iCarpetaId === 0 ? NULL : $request->iCarpetaId,
                $request->iPersId                                      ??  NULL,
                pathinfo($nombreOriginal, PATHINFO_FILENAME)           ??  NULL,
                $extension                                             ??  NULL,
                $ruta                                                  ??  NULL,
                $size                                                  ??  NULL,

                $request->iCredId                                      ??  NULL

            ];

            $data = DB::select(
                'exec repo.SP_INS_archivos
                    @_iCarpetaId=?,
                    @_iPersId=?,
                    @_cNombre=?,
                    @_cExtension=?,
                    @_cRuta=?,
                    @_cTamano=?,
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iArchivoId > 0) {
                return new JsonResponse(
                    ['validated' => true, 'message' => 'Se ha guardado exitosamente ', 'data' => null],
                    Response::HTTP_OK
                );
            } else {
                return new JsonResponse(
                    ['validated' => false, 'message' => 'No se ha podido guardar', 'data' => null],
                    Response::HTTP_OK
                );
            }
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    private function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }

    public function descargarArchivo($iArchivoId, Request $request)
    {
        try {
            $request->merge(['iArchivoId' => $iArchivoId]);
            $fieldsToDecode = ['iArchivoId'];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $data = DB::select('EXEC repo.Sp_SEL_archivosxiArchivoId @_iArchivoId = ?', [$request->iArchivoId]);

            if (empty($data)) {
                return new JsonResponse(
                    ['validated' => false, 'message' => 'Archivo no encontrado', 'data' => null],
                    Response::HTTP_NOT_FOUND
                );
            }

            $archivo = $data[0];
            $path = $archivo->cRuta;

            if (!Storage::exists($path)) {
                return new JsonResponse(
                    ['validated' => false, 'message' => 'El archivo no existe en el servidor', 'data' => null],
                    Response::HTTP_NOT_FOUND
                );
            }

            $contenido = base64_encode(Storage::get($path));
            $mime = Storage::mimeType($path);
            $nombre = $archivo->cNombre . '.' . $archivo->cExtension;

            return new JsonResponse(
                [
                    'validated' => true,
                    'message' => 'Se ha obtenido exitosamente ',
                    'nombre' => $nombre,
                    'mime' => $mime,
                    'base64' => $contenido,
                ],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => $e->getMessage(), 'data' => null],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    public function eliminarArchivo(Request $request, $iArchivoId)
    {
        try {
            $request->merge(['iArchivoId' => $iArchivoId]);

            $fieldsToDecode = ['iArchivoId', 'iCredId'];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $archivo = DB::table('repo.archivos')
                ->where('iArchivoId', $request->iArchivoId)
                ->first();

            if (!$archivo) {
                return response()->json([
                    'validated' => false,
                    'message' => 'No se encontró el archivo.',
                ], 404);
            }

            if (Storage::exists($archivo->cRuta)) {
                Storage::delete($archivo->cRuta);
            }

            $parametros = [
                $request->iArchivoId,
                $request->iCredId ?? null,
            ];

            $data = DB::select('EXEC repo.SP_DEL_archivos @_iArchivoId = ?, @_iCredId = ?', $parametros);

            if ($data[0]->iArchivoId > 0) {
                return new JsonResponse(
                    ['validated' => true, 'message' => 'Se ha eliminado exitosamente ', 'data' => null],
                    Response::HTTP_OK
                );
            } else {
                return new JsonResponse(
                    ['validated' => false, 'message' => 'No se ha podido eliminar', 'data' => null],
                    Response::HTTP_OK
                );
            }
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? $e->getMessage(), 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
