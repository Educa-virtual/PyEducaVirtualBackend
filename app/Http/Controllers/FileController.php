<?php

namespace App\Http\Controllers;

use App\Helpers\CollectionStrategy;
use App\Helpers\ResponseHandler;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function descargarArchivo(Request $request)
    {   
        if (ob_get_level() > 0 && ob_get_length() > 0) {
            Log::warning('Output buffer no vacío antes de enviar archivo: ' . bin2hex(ob_get_contents()));
            ob_clean(); // limpia el buffer antes de continuar
        }
        
        $archivo = $request->archivo;
        if (! Storage::disk('local')->exists($archivo)) {
            throw new Exception('El archivo no existe');
        }
        $ruta = $request->ruta;
        if (! Storage::disk('public')->exists($ruta)) {
            throw new Exception('El archivo no existe');
        }

        $path = Storage::disk('public')->path($ruta);
        $nombreArchivo = basename($ruta);

        return response()->download($path, $nombreArchivo);
    }

    public function uploadFile(Request $request)
    {
        $file = $request->file('file');
        $path = $file->store('uploads');

        return response()->json(['path' => $path]);
    }

    public function downloadFile(Request $request)
    {
        $path = $request->input('template');

        return response()->download(storage_path("templates/import/bulk-data/$path.xlsx"), basename($path), [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function importarEstudiantesMatriculasExcelPlatform(Request $request)
    {

        try {
            $query = (new ApiController(new CollectionStrategy))->execProcedure(
                $request,
                'sp_importar_estudiantes_matriculas_excel',
                [
                    'iSedeId' => $request->iSedeId,
                    'iSemAcadId' => null,
                    'iYAcadId' => $request->iYAcadId,
                    'iCredId' => $request->iCredId,
                    'tipo' => $request->tipo,
                    'json' => $request->json,
                ],
                [
                    'd',
                ]
            );

            return ResponseHandler::success($query, 'Auditoría de accesos obtenida correctamente.');
        } catch (\Exception $e) {
            return ResponseHandler::error('Error al obtener la auditoría de accesos.', 500, $e->getMessage());
            // throw $th;
        }
    }
}
