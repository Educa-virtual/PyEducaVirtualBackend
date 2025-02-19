<?php

namespace App\Http\Controllers\Ere;

use App\Http\Controllers\Controller;
use App\Repositories\Acad\AreasRepository;
use App\Repositories\Ere\EvaluacionesRepository;
use ErrorException;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AreasController extends Controller
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function guardarArchivoPdf($evaluacionId, $areaId, Request $request)
    {
        $evaluacionIdDescifrado = $this->hashids->decode($evaluacionId);
        $areaIdDescifrado = $this->hashids->decode($areaId);
        if (empty($evaluacionIdDescifrado) || empty($areaIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], 400);
        }
        $evaluacion = EvaluacionesRepository::obtenerEvaluacionPorId($evaluacionIdDescifrado[0]);
        if ($evaluacion == null) {
            return response()->json(['status' => 'Error', 'message' => 'No existe la evaluación con el ID enviado.'], 404);
        }
        $area = AreasRepository::obtenerAreaPorNivelGradId($areaIdDescifrado[0]);
        if ($area == null) {
            return response()->json(['status' => 'Error', 'message' => 'No existe el área con el ID enviado.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'archivo' => 'required|file|mimes:pdf|max:51200', // máximo 50MB
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return response()->json(['status' => 'Error', 'message' => $error[0]], 422); //422 Unprocessable Content
        } else {

            $archivo = $request->file('archivo');
            $nombreArchivo = 'examen.pdf';
            $rutaDestino = public_path("ere/evaluaciones/$evaluacionId/areas/$areaId");
            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0755, true);
            }
            $archivo->move($rutaDestino, $nombreArchivo);

            return response()->json(['status' => 'Success', 'message' => 'Archivo guardado correctamente.'], 200);
        }
    }

    public function descargarArchivoPdf($evaluacionId, $areaId)
    {
        $evaluacionIdDescifrado = $this->hashids->decode($evaluacionId);
        $areaIdDescifrado = $this->hashids->decode($areaId);
        if (empty($evaluacionIdDescifrado) || empty($areaIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], 400);
        }
        $evaluacion = EvaluacionesRepository::obtenerEvaluacionPorId($evaluacionIdDescifrado[0]);
        if ($evaluacion == null) {
            return response()->json(['status' => 'Error', 'message' => 'No existe la evaluación con el ID enviado.'], 404);
        }
        $area = AreasRepository::obtenerAreaPorNivelGradId($areaIdDescifrado[0]);
        if ($area == null) {
            return response()->json(['status' => 'Error', 'message' => 'No existe el área con el ID enviado.'], 404);
        }

        $rutaArchivo = public_path("ere/evaluaciones/$evaluacionId/areas/$areaId/examen.pdf");
        if (!file_exists($rutaArchivo)) {
            //return response()->json(['status' => 'Error', 'message' => 'Archivo no encontrado.'], 404);
            abort(404);
        }
        $nombreArchivo = $evaluacion->cEvaluacionNombre . ' - ' . ucwords(strtolower($area->cCursoNombre)) . ' ' . $area->cGradoAbreviacion . ' '
            . str_replace('Educación ', '', $area->cNivelTipoNombre) . '.pdf';
        return response()->download($rutaArchivo, $nombreArchivo, [
            'Content-Type' => 'application/pdf'
        ]);
    }
}
