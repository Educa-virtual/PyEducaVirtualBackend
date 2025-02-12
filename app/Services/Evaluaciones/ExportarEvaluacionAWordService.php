<?php

namespace App\Services\Evaluaciones;

use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportarEvaluacionAWordService
{

    private $evaluacion;
    private $area;
    private $preguntas;
    private $plantilla = 'template-ere.docx';
    private $phpTemplateWord;

    public function __construct($evaluacion, $area, $preguntas)
    {
        $this->evaluacion = $evaluacion;
        $this->area = $area;
        $this->preguntas = $preguntas;
        $this->phpTemplateWord = new TemplateProcessor(storage_path() . DIRECTORY_SEPARATOR . $this->plantilla);
    }

    private function obtenerAnioDeEvaluacion()
    {
        return $this->evaluacion->dtEvaluacionFechaInicio == null ? '' : (new Carbon($this->evaluacion->dtEvaluacionFechaInicio))->year;
    }

    private function prepararPaginasIniciales()
    {
        $anioEvaluacion = $this->obtenerAnioDeEvaluacion();
        $this->phpTemplateWord->setValue('cantidadPreguntas', count($this->preguntas));
        $this->phpTemplateWord->setValue('anioEval', $anioEvaluacion);
        $this->phpTemplateWord->setValue('nivelEval', strtoupper($this->evaluacion->cNivelEvalNombre));
        $this->phpTemplateWord->setValue('areaNombre', $this->area->cCursoNombre);
        $this->phpTemplateWord->setValue('grado', substr($this->area->cGradoAbreviacion, 0, 1));
        $this->phpTemplateWord->setValue('nivel', strtoupper(str_replace('Educación ', '', $this->area->cNivelTipoNombre)));
    }

    private function limpiarTexto($texto)
    {
        $textoLimpio = preg_replace('/<img[^>]+>/i', '', $texto);
        $textoLimpio = str_replace("&nbsp;", " ", $textoLimpio);
        $textoLimpio = html_entity_decode($textoLimpio, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $textoLimpio = strip_tags($textoLimpio);
        return $textoLimpio;
    }

    private function textoTieneImagen($texto)
    {
        return strpos($texto, ';base64,');
    }

    private function obtenerImagenDesdeBase64($texto)
    {
        preg_match('/<img.*?src=["\'](data:image\/[^"\']+)["\']/', $texto, $matches);
        return isset($matches[1]) ? $matches[1] : null;
    }

    private function obtenerDimensionesImagenDesdeBase64($imagen)
    {
        $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $imagen));
        return getimagesizefromstring($imageData);
    }

    private function insertarImagenEnEnunciado($indice, $texto)
    {
        $imagen = $this->obtenerImagenDesdeBase64($texto);
        $dimensiones = $this->obtenerDimensionesImagenDesdeBase64($imagen);
        $this->phpTemplateWord->setImageValue('cPreguntaImagen#' . $indice, array('path' => $imagen, 'width' => $dimensiones[0], 'height' => $dimensiones[1], 'ratio' => true));
    }

    private function generarPreguntas()
    {
        $this->phpTemplateWord->cloneBlock('block_preguntas', count($this->preguntas), true, true);

        foreach ($this->preguntas as $indexPregunta => $pregunta) {
            $this->phpTemplateWord->setValue('index#' . ($indexPregunta + 1), $indexPregunta + 1);
            $textoLimpio = $this->limpiarTexto($pregunta->cPregunta);
            // Si la pregunta tiene una imagen en base64, se inserta como imagen
            if ($this->textoTieneImagen($pregunta->cPregunta)) {

                $this->phpTemplateWord->setValue('cPreguntaTexto#' . ($indexPregunta + 1), $textoLimpio . "\r\n");
                $this->insertarImagenEnEnunciado(($indexPregunta + 1), $pregunta->cPregunta);
            } else {
                $this->phpTemplateWord->setValue('cPreguntaTexto#' . ($indexPregunta + 1), $textoLimpio);
                $this->phpTemplateWord->setValue('cPreguntaImagen#' . ($indexPregunta + 1), '');
            }

            // Si la pregunta tiene alternativas, se agregan al documento
            if (isset($pregunta->alternativas)) {
                $this->phpTemplateWord->cloneBlock('block_alternativas#' . ($indexPregunta + 1), count($pregunta->alternativas), true, true);
                usort($pregunta->alternativas, function ($a, $b) {
                    return strcmp($a->cAlternativaLetra, $b->cAlternativaLetra);
                });
                foreach ($pregunta->alternativas as $indexAlternativa => $alternativa) {
                    // Reemplazar valores de las alternativas dinámicamente
                    $this->phpTemplateWord->setValue('cAlternativaLetra#' . ($indexPregunta + 1) . '#' . ($indexAlternativa + 1), $alternativa->cAlternativaLetra);
                    $this->phpTemplateWord->setValue('cAlternativaDescripcion#' . ($indexPregunta + 1) . '#' . ($indexAlternativa + 1), strip_tags($alternativa->cAlternativaDescripcion));
                }
            }
        }
    }

    private function generarSalida()
    {
        $plantilla = $this->phpTemplateWord;
        $response = new StreamedResponse(function () use ($plantilla) {
            $plantilla->saveAs('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $response->headers->set('Content-Disposition', 'attachment; filename="Preguntas ' . $this->evaluacion->cEvaluacionNombre . '.docx"');
        $response->headers->set('Cache-Control', 'max-age=0');
        return $response;
    }

    public function generarResultado()
    {
        $this->prepararPaginasIniciales();
        $this->generarPreguntas();
        return $this->generarSalida();
    }
}
