<?php

namespace App\Http\Controllers\ere;

use App\Http\Controllers\Controller;
use App\Repositories\AlternativaPreguntaRespository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\Html;


class TestWordController extends Controller
{
    protected $alternativaRepository;

    public function __construct(AlternativaPreguntaRespository $alternativaRepository)
    {
        $this->alternativaRepository = $alternativaRepository;
    }

    public function word()
    {
        $preguntasDB  = DB::select('exec ere.SP_SEL_bancoPreguntas @_iCursoId = ?,
             @_busqueda = ?, @_iTipoPregId = ?, @_bPreguntaEstado = ?
            ', [1, '', 0, -1]);

        $preguntas = [];
        foreach ($preguntasDB as &$pregunta) {
            $preguntaOutput = '';
            $preguntaOutput .= $pregunta->cPregunta;
            // verificar si existe textoAyuda
            if ($pregunta->cPreguntaTextoAyuda != null && strlen($pregunta->cPreguntaTextoAyuda) > 0) {
                $preguntaOutput .= $pregunta->cPreguntaTextoAyuda;
            }
            // manejar alternativas.
            $pregunta->alternativas  = $this->alternativaRepository->getAllByPreguntaId($pregunta->iPreguntaId);
            foreach ($pregunta->alternativas as &$alternativa) {
                $preguntaOutput .= '<p>';
                $preguntaOutput .= $alternativa->cAlternativaLetra . ' ' . $alternativa->cAlternativaDescripcion;
                $preguntaOutput .= '</p>';
            }
            array_push($preguntas, $preguntaOutput);
            $preguntaOutput = '';
        }

        $phpWord = new PhpWord;
        $phpWord->addTitleStyle(1, ['size' => 24, 'color' => '333333', 'bold' => true]);
        $phpWord->addTitleStyle(2, ['size' => 18, 'color' => '666666']);
        $phpWord->addTitleStyle(3, ['size' => 14, 'color' => '999999', 'italic' => true]);
        $section = $phpWord->addSection();



        foreach ($preguntas as $index => $questionHtml) {
            // Añadir un salto de página antes de cada pregunta (excepto la primera)
            if ($index > 0) {
                $section->addPageBreak();
            }
            // sanitizar cierres html
            $questionHtml =  $this->sanitizeHtml($questionHtml);
            // Convertir el HTML de la pregunta a contenido de PHPWord
            Html::addHtml($section, $questionHtml, false, false);
        }

        \PhpOffice\PhpWord\Settings::setZipClass(Settings::PCLZIP);
        $writer = IOFactory::createWriter($phpWord, 'Word2007');

        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $response->headers->set('Content-Disposition', 'attachment;filename="preguntas.docx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        ob_start();
        $writer->save('php://output');
        $content = ob_get_contents();
        ob_end_clean();

        $response->setContent($content);

        return $response;
    }

    function sanitizeHtml($html)
    {
        // Reemplaza las etiquetas <img> no cerradas con la forma auto-cerrada
        return preg_replace('/<img([^>]+)(?<!\/)>/', '<img$1 />', $html);
    }
}
