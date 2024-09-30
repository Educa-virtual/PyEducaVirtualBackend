<?php

namespace App\Http\Controllers\Ere;

use App\Repositories\PreguntasRepository;
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

class PreguntasController extends ApiController
{
    protected  $alternativaPreguntaRespository;

    public function __construct(AlternativaPreguntaRespository $alternativaPreguntaRespository)
    {
        $this->alternativaPreguntaRespository = $alternativaPreguntaRespository;
    }

    public function guardarActualizarPreguntaConAlternativas(Request $request)
    {
        $iEncabPregId = $request->encabezado['iEncabPregId'];




        DB::beginTransaction();

        // encabezado


        $iEncabPregId = (int) $request->encabezado['iEncabPregId'];
        if ($iEncabPregId === -1) {
            $iEncabPregId = null;
        } else {
            $paramsEncabezado = [
                'iEncabPregId' => (int) $request->encabezado['iEncabPregId'],
                'cEncabPregTitulo' => $request->encabezado['cEncabPregTitulo'],
                'cEncabPregContenido' => $request->encabezado['cEncabPregContenido'],
                'iCursoId' => 1,
                'iNivelGradoId' => 1,
                'iColumnValue' => 1,
                'cColumnName' => 'iEspecialistaId',
                'cSchemaName'  => 'ere'
            ];
            try {
                $resp =  PreguntasRepository::guardarActualizarPreguntaEncabezado($paramsEncabezado);
                $resp = $resp[0];
                $iEncabPregId = $resp->id;
            } catch (Exception $e) {
                DB::rollBack();
                return $this->errorResponse($e, 'Error al guardar el encabezado');
            }
        }

        // params pregunta

        $preguntas = $request->preguntas;
        $preguntasActualizar = $preguntas;
        $preguntasEliminar = $request->preguntasEliminar;

        foreach ($preguntasActualizar as $key => $pregunta) {

            $fechaActual = new DateTime();
            $fechaActual->setTime(0, 0, 0);
            $hora = $pregunta['iHoras'];
            $minutos = $pregunta['iMinutos'];
            $segundos = $pregunta['iSegundos'];
            $fechaActual->setTime($hora, $minutos, $segundos);
            $fechaConHora = $fechaActual->format('d-m-Y H:i:s');
            $iCursoId = 1;

            $params = [
                $pregunta['isLocal'] ?? false ? 0 : (int) $pregunta['iPreguntaId'],
                (int) $iCursoId,
                (int)$pregunta['iTipoPregId'],
                $pregunta['cPregunta'],
                $pregunta['cPreguntaTextoAyuda'] ?? '',
                (int)$pregunta['iPreguntaNivel'],
                (int)$pregunta['iPreguntaPeso'],
                $fechaConHora,
                $pregunta['bPreguntaEstado'],
                $pregunta['cPreguntaClave'],
                $iEncabPregId
            ];


            // pregunta
            $respPregunta = null;
            try {
                $respPregunta = DB::select('exec ere.Sp_INS_UPD_pregunta 
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
                , @_iEncabPregId = ?
            ', $params);
                $respPregunta = $respPregunta[0];
            } catch (Exception $e) {
                DB::rollBack();
                return $this->errorResponse($e, 'Error al guardar los datos');
            }

            // alternativas
            $alternativasActualizar  = $pregunta['alternativas'] ?? [];
            $alternativasEliminar   = $pregunta['alternativasEliminar'] ?? [];
            // eliminar alternativas
            foreach ($alternativasEliminar as $alternativa) {
                $paramsAlternativaEliminar = [
                    $alternativa['iAlternativaId']
                ];
                try {
                    $resp = DB::select('exec ere.Sp_DEL_alternativa_pregunta @_iAlternativaId = ?', $paramsAlternativaEliminar);

                    // $resp = $resp[0];
                } catch (Exception $e) {
                    DB::rollBack();
                    $defaultMessage = $this->returnError($e, 'Error al eliminar');
                    return $this->errorResponse($e, $defaultMessage);
                }
            }

            // guardar actualizar alternativas
            foreach ($alternativasActualizar as $alternativa) {

                try {
                    $paramsAlternativa = [
                        $alternativa['isLocal'] ?? false ? 0 : (int) $alternativa['iAlternativaId'],
                        (int) $respPregunta->id,
                        $alternativa['cAlternativaDescripcion'],
                        $alternativa['cAlternativaLetra'],
                        $alternativa['bAlternativaCorrecta'] ? 1 : 0,
                        $alternativa['cAlternativaExplicacion'] ?? ''
                    ];
                    $resp = $this->alternativaPreguntaRespository->guardarActualizarAlternativa($paramsAlternativa);
                } catch (Exception $e) {
                    DB::rollBack();
                    $message = $this->returnError($e, 'Error al guardar los cambios de la alternativa');
                    return $this->errorResponse($e->getMessage(), $message);
                }
            }
        }

        // eliminar preguntas
        foreach ($preguntasEliminar as $pregunta) {
            $alternativasEliminar = array_merge($pregunta['alternativas'], $pregunta['alternativasEliminadas'] ?? []);
            foreach ($alternativasEliminar as $alternativa) {
                $paramsAlternativaEliminar = [
                    $alternativa['iAlternativaId']
                ];
                try {
                    $resp = DB::select('exec ere.Sp_DEL_alternativa_pregunta @_iAlternativaId = ?', $paramsAlternativaEliminar);

                    $resp = $resp[0];
                } catch (Exception $e) {
                    DB::rollBack();
                    $defaultMessage = $this->returnError($e, 'Error al eliminar');
                    return $this->errorResponse($e, $defaultMessage);
                }
            }

            try {

                $resp = DB::select('exec ere.Sp_DEL_pregunta @_iPreguntaId = ?', [$pregunta['iPreguntaId']]);

                if (count($resp) === 0) {
                    return $this->errorResponse($resp, 'Error al eliminar la pregunta.');
                }

                $resp = $resp[0];
            } catch (Exception $e) {
                DB::rollBack();
                $message = $this->returnError($e, 'Error al eliminar la pregunta');
                return $this->errorResponse($e, $message);
            }
        }
        DB::commit();

        return $this->successResponse(null, 'Cambion realizados correctamente');
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
            'iCursoId' => $request->iCursoId,
            'busqueda' => $request->busqueda ?? '',
            'iTipoPregId' => $request->iTipoPregId,
            'bPreguntaEstado' => $request->bPreguntaEstado
        ];


        try {
            $preguntas = PreguntasRepository::obtenerBancoPreguntasByParams($params);

            return $this->successResponse(
                $preguntas,
                'Datos obtenidos correctamente'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Error al obtener los datos');
        }
    }


    public function obtenerEncabezadosPreguntas(Request $request)
    {
        $type = $request->type;

        $params = [
            'iNivelGradoId' => $request->iNivelGradoId,
            'iCursoId' => $request->iCursoId,
            'iEspecialistaId' => $request->iEspecialistaId,
        ];

        if ($type === 'eval') {
            $params['schema'] = 'eval';
        }

        try {
            $cabezeras = PreguntasRepository::obtenerCabecerasPregunta($params);
            return $this->successResponse($cabezeras, 'Datos obtenidos corrctamente');
        } catch (Exception $e) {

            return $this->errorResponse($e, 'Error al obtener los datos');
        }
    }


    public  function eliminarEncabezadoPreguntaById($id)
    {
        $params = [
            $id
        ];

        try {
            $resp = DB::select('exec ere.Sp_DEL_encabezado_pregunta @_iEncabPregId = ?', $params);
            if (count($resp) === 0) {
                return $this->successResponse(null, 'Error al eliminar');
            }
            $resp = $resp[0];
            return $this->successResponse($resp, $resp->mensaje);
        } catch (Exception $e) {
            $message = $this->returnError($e, 'Error al eliminar');
            return $this->errorResponse($e, $message);
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
            $preguntasDB = PreguntasRepository::obtenerBancoPreguntasByParams($params);

            $preguntas = [];

            foreach ($preguntasDB as &$pregunta) {
                if ($pregunta->iEncabPregId == -1) {
                    $preguntaOutput = '';
                    $preguntaOutput .= $pregunta->cPregunta;
                    // verificar si existe textoAyuda
                    if ($pregunta->cPreguntaTextoAyuda != null && strlen($pregunta->cPreguntaTextoAyuda) > 0) {
                        $preguntaOutput .= $pregunta->cPreguntaTextoAyuda;
                    }

                    if (isset($pregunta->alternativas) && is_array($pregunta->alternativas)) {
                        foreach ($pregunta->alternativas as &$alternativa) {
                            $preguntaOutput .= '<p>';
                            $preguntaOutput .= $alternativa->cAlternativaLetra . ' ' . $alternativa->cAlternativaDescripcion;
                            $preguntaOutput .= '</p>';
                        }
                    }
                    array_push($preguntas, $preguntaOutput);
                    $preguntaOutput = '';
                } else {
                    $preguntaOutput = "<h1>{$pregunta->cEncabPregTitulo}</h1>";
                    $preguntaOutput .= $pregunta->cEncabPregContenido;
                    foreach ($pregunta->preguntas as &$subPreguntas) {
                        $preguntaOutput .= $subPreguntas->cPregunta;
                        // verificar si existe textoAyuda
                        if ($subPreguntas->cPreguntaTextoAyuda != null && strlen($subPreguntas->cPreguntaTextoAyuda) > 0) {
                            $preguntaOutput .= $subPreguntas->cPreguntaTextoAyuda;
                        }
                        if (isset($subPreguntas->alternativas) && is_array($subPreguntas->alternativas)) {
                            foreach ($subPreguntas->alternativas as &$alternativa) {
                                $preguntaOutput .= '<p>';
                                $preguntaOutput .= $alternativa->cAlternativaLetra . ' ' . $alternativa->cAlternativaDescripcion;
                                $preguntaOutput .= '</p>';
                            }
                        }
                    }

                    array_push($preguntas, $preguntaOutput);
                    $preguntaOutput = '';
                }
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

    public function guardarActualizarEncabezadoPregunta(Request $request)
    {

        $type = $request->type;

        try {
            $paramsEncabezado = [
                'iEncabPregId' => $request->iEncabPregId,
                'cEncabPregTitulo' => $request->cEncabPregTitulo,
                'cEncabPregContenido' => $request->cEncabPregContenido,
                'iCursoId' =>  $request->iCursoId,
                'iNivelGradoId' => $request->iNivelGradoId,
                'iColumnValue' => $request->iEspecialistaId
            ];
            if ($type === 'eval') {
                $paramsEncabezado['cColumnName'] = 'iDocenteId';
                $paramsEncabezado['cSchemaName'] = 'eval';
            }
            $resp = PreguntasRepository::guardarActualizarPreguntaEncabezado($paramsEncabezado);
            if (count($resp) < 1) {
                DB::rollBack();
                return $this->errorResponse(null, 'Error al guardar el encabezado');
            }
            $resp = $resp[0];
            return $this->successResponse($resp, 'Datos guardados correctamente');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 'Error al guardar los datos');
        }
    }
}
