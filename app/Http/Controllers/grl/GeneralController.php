<?php

namespace App\Http\Controllers\grl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

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
}
