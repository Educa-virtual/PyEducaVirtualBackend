<?php

namespace App\Services\ere;

use App\Helpers\VerifyHash;
use App\Models\ere\Evaluacion;
use App\Repositories\acad\AreasRepository;
use App\Repositories\acad\DocentesRepository;
use App\Repositories\ere\EvaluacionesRepository;
use App\Repositories\grl\PersonasRepository;
use App\Repositories\grl\YearsRepository;
use App\Repositories\PreguntasRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AreasService
{
    /*public static function tieneArchivoErePdfSubido($iEvaluacionid, $iCursosNivelGradId)
    {
        return file_exists(public_path("ere/evaluaciones/$iEvaluacionid/areas/$iCursosNivelGradId/examen.pdf"));
    }*/

    public static function tieneArchivoErePdfSubido($iEvaluacionId, $iCursosNivelGradId)
    {
        $rutaArchivo = "ere/evaluaciones/$iEvaluacionId/areas/$iCursosNivelGradId/examen.pdf";
        return Storage::disk('public')->exists($rutaArchivo);
    }

    public static function guardarArchivoErePdf($request, $evaluacionId, $areaId)
    {
        $archivo = $request->file('archivo');
        $nombreArchivo = 'examen.pdf';
        $rutaDestino = "ere/evaluaciones/$evaluacionId/areas/$areaId";

        if (!Storage::disk('public')->exists($rutaDestino)) {
            Storage::disk('public')->makeDirectory($rutaDestino);
        }
        $archivo->move(Storage::disk('public')->path($rutaDestino), $nombreArchivo);
    }

    public static function eliminarArchivoErePdf($evaluacionId, $areaId)
    {
        $rutaArchivo = "ere/evaluaciones/$evaluacionId/areas/$areaId/examen.pdf";
        if (Storage::disk('public')->exists($rutaArchivo)) {
            Storage::disk('public')->delete($rutaArchivo);
        } else {
            throw new Exception('El archivo no existe');
        }
    }

    public static function obtenerCartillaRespuestas($evaluacionId, $areaId)
    {
        $rutaArchivo = "ere/evaluaciones/hoja-respuestas/Hoja respuestas.docx";
        if (!Storage::disk('public')->exists($rutaArchivo)) {
            throw new Exception('El archivo no existe');
        }
        return $rutaArchivo;
    }

    public static function obtenerAreasPorEvaluacion($evaluacionId, $iPersId)
    {
        $evaluacionIdDescifrado =  VerifyHash::decodesxId($evaluacionId);
        if (empty($evaluacionIdDescifrado)) {
            throw new Exception('El ID enviado no se pudo descifrar.');
        }
        $resultados = AreasRepository::obtenerAreasPorEvaluacion($evaluacionIdDescifrado, $iPersId, request()->header('icredentperfid'));

        if ($resultados==null || count($resultados) == 0) {
            throw new Exception('No hay áreas disponibles.');
        }

        foreach ($resultados as $fila) {
            $fila->iCantidadMaximaPreguntas = Evaluacion::selCantidadMaxPreguntas($evaluacionIdDescifrado, $fila->iCursosNivelGradId) ?? 20; //EvaluacionesRepository::selCantidadMaxPreguntas($evaluacionIdDescifrado, $fila->iCursosNivelGradId);
            $fila->iCantidadPreguntas = PreguntasRepository::obtenerCantidadPreguntasPorEvaluacion($evaluacionIdDescifrado, $fila->iCursosNivelGradId);
            $fila->iEvaluacionId = $evaluacionIdDescifrado;
            $fila->iCursosNivelGradId = VerifyHash::encodexId($fila->iCursosNivelGradId);
            $fila->bTieneArchivo = AreasService::tieneArchivoErePdfSubido($evaluacionId, $fila->iCursosNivelGradId);
            $fila->iEvaluacionIdHashed = $evaluacionId;
        }
        return $resultados;
    }

    public static function actualizarEstadoDescarga($evaluacionId, $areaId, $bDescarga)
    {
        $evaluacionIdDescifrado = VerifyHash::decodesxId($evaluacionId);
        $areaIdDescifrado = VerifyHash::decodesxId($areaId);
        if (empty($evaluacionIdDescifrado) || empty($areaIdDescifrado)) {
            throw new Exception('El ID enviado no se pudo descifrar.');
        }
        AreasRepository::actualizarEstadoDescarga($evaluacionIdDescifrado, $areaIdDescifrado, $bDescarga);
    }

    public static function obtenerArchivoErePdf($evaluacion, $area)
    {
        $fechaInicio = new Carbon($evaluacion->dtEvaluacionFechaInicio);
        $rutaArchivo = "ere/evaluaciones/$evaluacion->evaluacionIdHashed/areas/$area->areaIdCifrado/examen.pdf";
        if (!Storage::disk('public')->exists($rutaArchivo)) {
            throw new Exception('El archivo no existe');
        }
        $data = [];
        $data['contenido'] = Storage::disk('public')->get($rutaArchivo);
        $data['nombreArchivo'] = ucwords(strtolower($area->cCursoNombre)) . '-' . $area->cGradoAbreviacion . '-'
            . str_replace('Educación ', '', $area->cNivelTipoNombre) . '-' . $fechaInicio->year . '.pdf';
        return $data;
    }

    public static function generarMatrizCompetencias($evaluacionId, $areaId, $usuario)
    {
        date_default_timezone_set('America/Lima');
        $evaluacionIdDescifrado = VerifyHash::decodesxId($evaluacionId);
        $areaIdDescifrado = VerifyHash::decodesxId($areaId);
        if (empty($evaluacionIdDescifrado) || empty($areaIdDescifrado)) {
            throw new Exception('El ID enviado no se pudo descifrar.');
        }
        $year = YearsRepository::obtenerYearPorId(date('Y'));

        $persona = PersonasRepository::obtenerPersonaPorId($usuario->iPersId);
        $evaluacion = EvaluacionesRepository::obtenerEvaluacionPorId($evaluacionIdDescifrado);
        if ($evaluacion == null) {
            throw new Exception('No existe la evaluación con el ID enviado.');
        }
        $area = AreasRepository::obtenerAreaPorNivelGradId($areaIdDescifrado);
        if ($area == null) {
            throw new Exception('No existe el área con el ID enviado.');
        }

        $dataMatriz = AreasRepository::obtenerMatrizPorEvaluacionArea($evaluacionIdDescifrado, $areaIdDescifrado);
        if (empty($dataMatriz)) {
            throw new Exception('No hay preguntas para generar la matriz.');
        }
        //Validar si es docente o director y si puede descargar el archivo
        //DB::statement("EXEC [ere].Sp_SEL_validarDescargaArchivoFinEvaluacionPdf ?, ?", [request()->header('icredentperfid'), $evaluacionIdDescifrado]);
        $data = [
            'year' => $year,
            'dataMatriz' => $dataMatriz,
            'evaluacion' => $evaluacion,
            'area' => $area,
            'persona' => $persona
        ];
        $html = view('ere.areas.pdf.matriz-competencias', $data)->render();
        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        $pdf->render();

        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
        $font   = $dompdf->getFontMetrics()->getFont('Helvetica', 'normal');

        // Conversión mm -> pt
        $mm_to_pt = 2.8346;
        $page_height_mm = $canvas->get_height() / $mm_to_pt;
        $y_mm = $page_height_mm - 30;
        $y_pt = $y_mm * $mm_to_pt;

        // Agrega pie de página // X: 15mm desde el borde izquierdo// Y: calculado para quedar dentro del margen
        //$canvas->page_text(15 * $mm_to_pt, $y_pt, 'Página {PAGE_NUM} de {PAGE_COUNT}', $font, 10,  [0, 0, 0]);
        $nombrePersona = "Autor: " . ucfirst(strtolower($persona->cPersNombre)) . " " . ucfirst(strtolower($persona->cPersPaterno)) . " " . ucfirst(strtolower($persona->cPersMaterno));
        $font = null;
        $size = 10;
        $color = array(0, 0, 0);
        //Numero de pagina
        $canvas->page_text(30, 540, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, $size, $color, 0.0, 0.0, 0.0);
        //Persona
        $canvas->page_text(320, 540, ucwords($nombrePersona), $font, $size, $color, 0.0, 0.0, 0.0);
        //Fecha
        $canvas->page_text(715, 540, date("d/m/Y H:i:s"), $font, $size, $color, 0.0, 0.0, 0.0);
        return $pdf->stream('matriz_competencias.pdf');
    }
}
