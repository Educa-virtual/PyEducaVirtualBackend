<?php

namespace App\Http\Controllers\grl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class GeneralController extends Controller
{
    public function subirArchivo(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'file' => 'required|mimes:pdf,jpeg,png'
            ],
            [
                'file.required' => 'Es necesario que cargue un archivo',
                'file.mimes' => 'El archivo debe ser formato PDF, JPEG o PNG; seleccione otro archivo.',
            ]
        );

        if ($validator->fails()) {
            return new JsonResponse(['validated' => false, 'message' => $validator->errors(), 'data' => []], 422);
        }

        if ($request->hasFile('file')) {

            $file = $request->file('file');
            $path = $request->file("file")->store($request->nameFile, ['disk' => 'file']);
            return new JsonResponse(['validated' => true, 'message' => 'Se guardó exitosamente el archivo', 'data' => $path], 200);
            return response()->json($path);
        } else {
            return new JsonResponse(['validated' => false, 'message' => 'No se adjuntaron archivos', 'data' => []], 503);
        }
    }

    public function removerArchivo(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'data' => 'required|string'
            ],
            [
                'data.required' => 'La ruta del archivo es obligatoria.',
            ]
        );

        if ($validator->fails()) {
            return new JsonResponse(['validated' => false, 'message' => $validator->errors(), 'data' => []], 422);
        }

        $ruta = $request->input('data');

        if (Storage::disk('file')->exists($ruta)) {
            Storage::disk('file')->delete($ruta);
            return new JsonResponse([
                'validated' => true,
                'message' => 'El archivo se eliminó correctamente',
                'data' => []
            ], 200);
        } else {
            return new JsonResponse([
                'validated' => false,
                'message' => 'El archivo no existe en el servidor',
                'data' => []
            ], 404);
        }
    }

    public function subirSvgPizarra(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'file' => 'required|mimetypes:image/svg+xml,text/plain,text/xml,application/xml,application/octet-stream,application/pdf,image/jpeg,image/png'
            ],
            [
                'file.required' => 'Es necesario que cargue un archivo',
                'file.mimetypes' => 'El archivo debe ser PDF, JPEG, PNG o SVG válido.',
            ]
        );

        if ($validator->fails()) {
            return new JsonResponse(['validated' => false, 'message' => $validator->errors(), 'data' => []], 422);
        }

        if ($request->hasFile('file')) {

            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension() ?: 'svg';
            $filename = uniqid('pizarra_') . '.' . $ext;

            $path = $file->storeAs($request->nameFile, $filename, ['disk' => 'file']);

            return new JsonResponse(['validated' => true, 'message' => 'Se guardó exitosamente el archivo', 'data' => $path], 200);
            return response()->json($path);
        } else {
            return new JsonResponse(['validated' => false, 'message' => 'No se adjuntaron archivos', 'data' => []], 503);
        }
    }
}
