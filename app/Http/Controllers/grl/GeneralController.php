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
            $path = $request->file("file")->store('evaluaciones', ['disk' => 'file']);
            return response()->json($path);
        } else {
            abort(503, 'No se adjuntaron archivos');
        }
    }
}
