<?php

namespace App\Http\Controllers\ere;

use App\Http\Controllers\Controller;
use App\Repositories\acad\AreasRepository;
use App\Repositories\acad\DocentesRepository;
use App\Repositories\ere\EvaluacionesRepository;
use App\Repositories\grl\PersonasRepository;
use App\Repositories\grl\YearsRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use ErrorException;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;

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
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }
        $evaluacion = EvaluacionesRepository::obtenerEvaluacionPorId($evaluacionIdDescifrado[0]);
        if ($evaluacion == null) {
            return response()->json(['status' => 'Error', 'message' => 'No existe la evaluación con el ID enviado.'], Response::HTTP_NOT_FOUND);
        }
        $area = AreasRepository::obtenerAreaPorNivelGradId($areaIdDescifrado[0]);
        if ($area == null) {
            return response()->json(['status' => 'Error', 'message' => 'No existe el área con el ID enviado.'], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'archivo' => 'required|file|mimes:pdf|max:51200', // máximo 50MB
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return response()->json(['status' => 'Error', 'message' => $error[0]], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {

            $archivo = $request->file('archivo');
            $nombreArchivo = 'examen.pdf';
            $rutaDestino = public_path("ere/evaluaciones/$evaluacionId/areas/$areaId");
            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0755, true);
            }
            $archivo->move($rutaDestino, $nombreArchivo);
            return response()->json(['status' => 'Success', 'message' => 'Archivo guardado correctamente.'], Response::HTTP_OK);
        }
    }

    private function descargarArchivoPreguntasPdf($evaluacion, $area)
    {
        $rutaArchivo = public_path("ere/evaluaciones/$evaluacion->evaluacionidCifrado/areas/$area->areaIdCifrado/examen.pdf");
        if (!file_exists($rutaArchivo)) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $nombreArchivo = $evaluacion->cEvaluacionNombre . ' - ' . ucwords(strtolower($area->cCursoNombre)) . ' ' . $area->cGradoAbreviacion . ' '
            . str_replace('Educación ', '', $area->cNivelTipoNombre) . '.pdf';

        return response()->download($rutaArchivo, $nombreArchivo, [
            'Content-Type' => 'application/pdf'
        ]);
    }

    private function descargarArchivoPreguntasWord($evaluacion, $area)
    {
        $url = "http://localhost:7500/api/ere/evaluaciones/$evaluacion->evaluacionidCifrado/areas/$area->areaIdCifrado/archivo-preguntas";
        $response = Http::withOptions(['stream' => true])->get($url);
        if ($response->failed()) {
            abort(404, 'Archivo no encontrado.');
        }
        $contenido = $response->body();
        $nombreArchivo = 'archivo.docx';
        $contentDisposition = $response->header('Content-Disposition');
        // Si el header existe, intentamos extraer el nombre del archivo
        if ($contentDisposition && preg_match('/filename\*?=(?:UTF-8\'\')?["\']?([^"\';]+)/i', $contentDisposition, $matches)) {
            $nombreArchivo = $matches[1];
        }
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => "attachment; filename=\"$nombreArchivo\"",
        ];
        return response($contenido, 200, $headers);
    }

    public function descargarArchivoPreguntas($evaluacionId, $areaId, Request $request)
    {
        $evaluacionIdDescifrado = $this->hashids->decode($evaluacionId);
        $areaIdDescifrado = $this->hashids->decode($areaId);
        if (empty($evaluacionIdDescifrado) || empty($areaIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }
        $evaluacion = EvaluacionesRepository::obtenerEvaluacionPorId($evaluacionIdDescifrado[0]);
        if ($evaluacion == null) {
            return response()->json(['status' => 'Error', 'message' => 'No existe la evaluación con el ID enviado.'], Response::HTTP_NOT_FOUND);
        }
        $area = AreasRepository::obtenerAreaPorNivelGradId($areaIdDescifrado[0]);
        if ($area == null) {
            return response()->json(['status' => 'Error', 'message' => 'No existe el área con el ID enviado.'], Response::HTTP_NOT_FOUND);
        }
        $evaluacion->evaluacionidCifrado = $evaluacionId;
        $area->areaIdCifrado = $areaId;
        switch ($request->query('tipo')) {
            case 'pdf':
                return $this->descargarArchivoPreguntasPdf($evaluacion, $area);
            case 'word':
                return $this->descargarArchivoPreguntasWord($evaluacion, $area);
            default:
                return response()->json(['status' => 'Error', 'message' => 'Tipo de archivo no soportado.'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function generarMatrizCompetencias($evaluacionId, $areaId, Request $request)
    {
        date_default_timezone_set('America/Lima');
        $docenteIdDescifrado = $this->hashids->decode($request->input('docente'));
        $evaluacionIdDescifrado = $this->hashids->decode($evaluacionId);
        $areaIdDescifrado = $this->hashids->decode($areaId);
        if (empty($evaluacionIdDescifrado) || empty($areaIdDescifrado) || empty($docenteIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }
        $year = YearsRepository::obtenerYearPorId(date('Y'));
        $docente = DocentesRepository::obtenerDocentePorId($docenteIdDescifrado[0]);
        if ($docente == null) {
            return response()->json(['status' => 'Error', 'message' => 'No existe el docente con el ID enviado.'], Response::HTTP_NOT_FOUND);
        }
        $persona = PersonasRepository::obtenerPersonaPorId($docente->iPersId);
        $evaluacion = EvaluacionesRepository::obtenerEvaluacionPorId($evaluacionIdDescifrado[0]);
        if ($evaluacion == null) {
            return response()->json(['status' => 'Error', 'message' => 'No existe la evaluación con el ID enviado.'], Response::HTTP_NOT_FOUND);
        }
        $area = AreasRepository::obtenerAreaPorNivelGradId($areaIdDescifrado[0]);
        if ($area == null) {
            return response()->json(['status' => 'Error', 'message' => 'No existe el área con el ID enviado.'], Response::HTTP_NOT_FOUND);
        }

        $dataMatriz = AreasRepository::obtenerMatrizPorEvaluacionArea($evaluacionIdDescifrado[0], $areaIdDescifrado[0]);
        if (empty($dataMatriz)) {
            return response()->json(['status' => 'Error', 'message' => 'No hay preguntas para generar la matriz.'], Response::HTTP_NOT_FOUND);
        }
        $data = [
            'year' => $year,
            'dataMatriz' => $dataMatriz,
            'evaluacion' => $evaluacion,
            'area' => $area,
            'persona' => $persona
        ];
        $pdf = PDF::loadView('ere.areas.pdf.matriz-competencias', $data)->setPaper('a4', 'landscape')->set_option("enable_php", true);
        return $pdf->download('Matriz competencias - ' . $evaluacion->cEvaluacionNombre . '.pdf');
    }

    public function actualizarLiberacionAreasPorEvaluacion($evaluacionId)
    {
        $evaluacionIdDescifrado = $this->hashids->decode($evaluacionId);
        if (empty($evaluacionIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }
        AreasRepository::liberarAreasPorEvaluacion($evaluacionIdDescifrado[0]);
        return response()->json(['status' => 'Success', 'message' => 'Se han liberado las áreas de la evaluación especificada.'], Response::HTTP_OK);
    }
}
