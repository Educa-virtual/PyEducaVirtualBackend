<?php

namespace App\Services\ere\preguntas;

use App\Repositories\acad\AreasRepository;
use App\Repositories\PreguntasRepository;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportarPreguntasPorAreaWordService
{

    private $evaluacion;
    private $area;
    private $preguntas;
    private $plantilla = 'template-ere-';
    private $phpTemplateWord;
    private $anioEvaluacion;

    public function __construct($evaluacion, $area, $preguntas)
    {
        $this->evaluacion = $evaluacion;
        $this->area = $area;
        $this->preguntas = $preguntas;
        $this->anioEvaluacion = $this->obtenerAnioDeEvaluacion();
        $this->phpTemplateWord = new TemplateProcessor(storage_path() . DIRECTORY_SEPARATOR . $this->plantilla . $this->anioEvaluacion . '.docx');
    }

    private function obtenerAnioDeEvaluacion()
    {
        return $this->evaluacion->dtEvaluacionFechaInicio == null ? '2025' : (new Carbon($this->evaluacion->dtEvaluacionFechaInicio))->year;
    }

    private function prepararPaginasIniciales()
    {   //dd($this->evaluacion);
        $this->phpTemplateWord->setValue('cantidadPreguntas', PreguntasRepository::contarPreguntasEre($this->preguntas));
        //$this->phpTemplateWord->setValue('anioEval', $anioEvaluacion);
        $this->phpTemplateWord->setValue('nivelEval', strtoupper($this->evaluacion->cNivelEvalNombre));
        $this->phpTemplateWord->setValue('areaNombre', $this->area->cCursoNombre);

        switch ($this->anioEvaluacion) {
            case '2024':
                $grado = substr($this->area->cGradoAbreviacion, 0, 1);
                break;
            case '2025':
                $grado = strtoupper($this->area->cGradoNombre);
                break;
            default:
                $grado = strtoupper($this->area->cGradoNombre);
                break;
        }
        $this->phpTemplateWord->setValue('grado', $grado);
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

    private function insertarImagen($ubicacion, $texto)
    {
        $imagen = $this->obtenerImagenDesdeBase64($texto);
        $dimensiones = $this->obtenerDimensionesImagenDesdeBase64($imagen);
        $this->phpTemplateWord->setImageValue($ubicacion, array('path' => $imagen, 'width' => $dimensiones[0], 'height' => $dimensiones[1], 'ratio' => true));
    }

    private function insertarPregunta($indexActual, $pregunta)
    {
        $this->phpTemplateWord->setValue('index#' . ($indexActual + 1), $indexActual + 1);
        $textoLimpio = $this->limpiarTexto($pregunta->cPregunta);
        $this->phpTemplateWord->setValue('preguntaTexto#' . ($indexActual + 1), $textoLimpio);
        if ($this->textoTieneImagen($pregunta->cPregunta)) {
            $this->insertarImagen('preguntaImagen#' . ($indexActual + 1), $pregunta->cPregunta);
        } else {
            $this->phpTemplateWord->setValue('preguntaImagen#' . ($indexActual + 1), '');
        }
        $this->insertarAlternativas($indexActual, $pregunta);
    }

    private function insertarEncabezado($indexActual, $contenido)
    {
        $textoLimpio = $this->limpiarTexto($contenido);
        $this->phpTemplateWord->setValue('encabezadoTexto#' . ($indexActual + 1), $textoLimpio);
        if ($this->textoTieneImagen($contenido)) {
            $this->insertarImagen('encabezadoImagen#' . ($indexActual + 1), $contenido);
        } else {
            $this->phpTemplateWord->setValue('encabezadoImagen#' . ($indexActual + 1), '');
        }
    }

    private function insertarAlternativas($indexActual, $pregunta)
    {
        if (isset($pregunta->alternativas)) {
            $this->phpTemplateWord->cloneBlock('block_alternativas#' . ($indexActual + 1), count($pregunta->alternativas), true, true);
            usort($pregunta->alternativas, function ($a, $b) {
                return strcmp($a->cAlternativaLetra, $b->cAlternativaLetra);
            });
            foreach ($pregunta->alternativas as $indexAlternativa => $alternativa) {
                $this->phpTemplateWord->setValue('alternativaLetra#' . ($indexActual + 1) . '#' . ($indexAlternativa + 1), $alternativa->cAlternativaLetra);
                $this->phpTemplateWord->setValue('alternativaDescripcion#' . ($indexActual + 1) . '#' . ($indexAlternativa + 1), strip_tags($alternativa->cAlternativaDescripcion));
            }
        }
    }

    private function borrarContenedorEncabezado($indice)
    {
        $this->phpTemplateWord->setValue('encabezadoTexto#' . $indice, '');
        $this->phpTemplateWord->setValue('encabezadoImagen#' . $indice, '');
    }

    private function generarContenido()
    {
        $cantidadPreguntas = PreguntasRepository::contarPreguntasEre($this->preguntas);
        $this->phpTemplateWord->cloneBlock('block_preguntas', $cantidadPreguntas, true, true);

        foreach ($this->preguntas as $indexPregunta => $pregunta) {
            if ($pregunta->iEncabPregId == '-1') {
                $this->borrarContenedorEncabezado(($indexPregunta + 1));
                $this->insertarPregunta($indexPregunta, $pregunta);
            } else {
                $this->insertarEncabezado($indexPregunta, $pregunta->cEncabPregContenido);
                $indiceActual = $indexPregunta;
                foreach ($pregunta->preguntas as $index => $preguntaEncabezado) {
                    $this->insertarPregunta($indiceActual, $preguntaEncabezado);
                    $indiceActual++;
                    $this->borrarContenedorEncabezado(($indiceActual + 1));
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

    public function exportar()
    {
        $this->prepararPaginasIniciales();
        $this->generarContenido();
        //die("FIN");
        return $this->generarSalida();
    }
}
