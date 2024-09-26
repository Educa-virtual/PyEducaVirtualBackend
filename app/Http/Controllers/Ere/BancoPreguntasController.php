<?php

namespace App\Http\Controllers\Ere;

use App\Repositories\BancoPreguntasRepository;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\WordController;
use App\Repositories\AlternativaPreguntaRespository;
use DateTime;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\Html;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class BancoPreguntasController extends ApiController
{
    protected  $alternativaPreguntaRespository;
    protected $bancoPreguntaRepository;

    public function __construct(AlternativaPreguntaRespository $alternativaPreguntaRespository, BancoPreguntasRepository $bancoPreguntaRepository)
    {
        $this->alternativaPreguntaRespository = $alternativaPreguntaRespository;
        $this->bancoPreguntaRepository = $bancoPreguntaRepository;
    }

    public function guardarActualizarPreguntaConAlternativas(Request $request)
    {
        $fechaActual = new DateTime();
        $fechaActual->setTime(0, 0, 0);
        $hora = $request->iHoras;
        $minutos = $request->iMinutos;
        $segundos = $request->iSegundos;
        $fechaActual->setTime($hora, $minutos, $segundos);
        $fechaConHora = $fechaActual->format('d-m-Y H:i:s');

        $params = [
            (int) $request->iPreguntaId,
            (int)$request->iCursoId,
            (int)$request->iTipoPregId,
            $request->cPregunta,
            $request->cPreguntaTextoAyuda,
            (int)$request->iPreguntaNivel,
            (int)$request->iPreguntaPeso,
            $fechaConHora,
            $request->bPreguntaEstado,
            $request->cPreguntaClave,
        ];

        DB::beginTransaction();
        $resp = null;
        try {
            $resp = DB::select('exec ere.Sp_INS_UPD_pregunta 
                @_iPreguntaId = ?
                , @_iCursoId = ?
                , @_iTipoPregId = ?
                , @_cPregunta = ?
                , @_cPreguntaTextoAyuda = ?
                , @_iPreguntaNivel  = ?
                , @_iPreguntaPeso = ?
                , @_dtPreguntaTiempo = ?
                , @_bPreguntaEstado = ?
                , @_cPreguntaClave = ?
            ', $params);
            $resp = $resp[0];
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e, 'Error al guardar los datos');
        }

        try {

            foreach ($request->datosAlternativas as $item) {
                $paramsAlternativa = [
                    $item['isLocal'] ?? false ? 0 : (int) $item['iAlternativaId'],
                    (int) $resp->id,
                    $item['cAlternativaDescripcion'],
                    $item['cAlternativaLetra'],
                    $item['bAlternativaCorrecta'] ? 1 : 0,
                    $item['cAlternativaExplicacion']
                ];
                $this->alternativaPreguntaRespository->guardarActualizarAlternativa($paramsAlternativa);
            }
        } catch (Exception $e) {
            DB::rollBack();
            $message = $this->returnError($e, 'Error al guardar los datos');
            return $this->errorResponse($e, $message);
        }

        DB::commit();
        return $this->successResponse($resp, $resp->mensaje);
    }
    public function actualizarMatrizPreguntas(Request $request)
    {

        $preguntas = $request->preguntas;

        if (!is_array($preguntas)) {
            return $this->errorResponse(null, 'Datos mal formateados');
        }

        try {
            foreach ($preguntas as $pregunta) {
                $pregunta['datosJson']['bPreguntaEstado'] = 1;
                $datosJson = json_encode($pregunta['datosJson']);

                $condiciones = [
                    [
                        'COLUMN_NAME' => "iPreguntaId",
                        'VALUE' => $pregunta['iPreguntaId']
                    ]
                ];
                $condicionesJson = json_encode($condiciones);

                $params = [
                    'ere',
                    'preguntas',
                    $datosJson,
                    $condicionesJson
                ];
                $resp = DB::statement(
                    'EXEC grl.SP_UPD_EnTablaConJSON
                        @Esquema = ?,
                        @Tabla = ?,
                        @DatosJSON = ?,
                        @CondicionesJSON = ?
                    ',
                    $params
                );
            }

            return $this->successResponse(
                $resp,
                'Datos guardados correctamente'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al actualizar los datos');
        }
    }

    public function obtenerBancoPreguntas(Request $request)
    {

        $params = [
            $request->iCursoId,
            $request->busqueda ?? '',
            $request->iTipoPregId,
            $request->bPreguntaEstado
        ];


        try {
            $preguntas = DB::select('exec ere.Sp_SEL_banco_preguntas @_iCursoId = ?,
             @_busqueda = ?, @_iTipoPregId = ?, @_bPreguntaEstado = ?
            ', $params);

            return $this->successResponse(
                $preguntas,
                'Datos obtenidos correctamente'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al obtener los datos');
        }
    }


    public function eliminarBancoPreguntasById(Request $request, $id)
    {
        $params = [
            $id
        ];

        try {

            $resp = DB::select('exec ere.Sp_DEL_pregunta @_iPreguntaId = ?', $params);

            if (count($resp) === 0) {
                return $this->errorResponse($resp, 'Error al eliminar la pregunta.');
            }

            $resp = $resp[0];

            return $this->successResponse($resp, $resp->mensaje);
        } catch (Exception $e) {
            $message = $this->returnError($e, 'Error al eliminar la pregunta');
            return $this->errorResponse($e, $message);
        }
    }


    public function generarWordBancoPreguntasByIds(Request $request)
    {

        $params = [
            'iCursoId' => $request->iCursoId,
            'busqueda' => '',
            'iTipoPregId' => 0,
            'bPreguntaEstado' => -1,
            'ids' => $request->ids
        ];

        try {
            $preguntasDB = $this->bancoPreguntaRepository->obtenerBancoPreguntasByParams($params);

            $preguntas = [];
            foreach ($preguntasDB as &$pregunta) {
                $preguntaOutput = '';
                $preguntaOutput .= $pregunta->cPregunta;
                // verificar si existe textoAyuda
                if ($pregunta->cPreguntaTextoAyuda != null && strlen($pregunta->cPreguntaTextoAyuda) > 0) {
                    $preguntaOutput .= $pregunta->cPreguntaTextoAyuda;
                }
                // manejar alternativas.
                $pregunta->alternativas  = $this->alternativaPreguntaRespository->getAllByPreguntaId($pregunta->iPreguntaId);
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
                $questionHtml =  WordController::sanitizeHtml($questionHtml);
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
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Error al generar');
        }
    }
}
