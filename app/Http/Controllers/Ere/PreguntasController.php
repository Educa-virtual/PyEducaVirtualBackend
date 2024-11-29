<?php

namespace App\Http\Controllers\Ere;

use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\Shared\Html;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\WordController;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Repositories\PreguntasRepository;
use App\Repositories\AlternativaPreguntaRespository;

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
                'iCursoId' => $request->iCursoId,
                'iNivelGradoId' => $request->iNivelGradoId,
                'iColumnValue' => $request->iEspecialistaId,
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

            $iPreguntaId = $pregunta['isLocal'] ?? false ? 0 : (int) $pregunta['iPreguntaId'];
            $params = [
                $iPreguntaId,
                (int) $iCursoId,
                (int)$pregunta['iTipoPregId'],
                $pregunta['cPregunta'],
                $pregunta['cPreguntaTextoAyuda'] ?? '',
                (int)$pregunta['iPreguntaNivel'],
                (int)$pregunta['iPreguntaPeso'],
                $fechaConHora,
                $iPreguntaId === 0 ? 0 : null,
                $pregunta['cPreguntaClave'],
                $iEncabPregId
            ];


            // pregunta
            $respPregunta = null;
            try {
                $respPregunta = DB::select('exec ere.SP_INS_UPD_pregunta
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
                return $this->errorResponse($e->getMessage(), 'Error al guardar los datos');
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
                    //Se cambio el nombre SP_DEL_alternativa_pregunta
                    $resp = DB::select('exec ere.SP_DEL_alternativaPregunta @_iAlternativaId = ?', $paramsAlternativaEliminar);

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
                    $resp = DB::select('exec ere.SP_DEL_alternativa_pregunta @_iAlternativaId = ?', $paramsAlternativaEliminar);

                    $resp = $resp[0];
                } catch (Exception $e) {
                    DB::rollBack();
                    $defaultMessage = $this->returnError($e, 'Error al eliminar');
                    return $this->errorResponse($e, $defaultMessage);
                }
            }

            try {

                $resp = DB::select('exec ere.SP_DEL_pregunta @_iPreguntaId = ?', [$pregunta['iPreguntaId']]);

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
            'iCursoId' => $request->iCursoId ?? 0,
            'busqueda' => $request->busqueda ?? '',
            'iTipoPregId' => $request->iTipoPregId ?? 0,
            'bPreguntaEstado' => $request->bPreguntaEstado ?? -1,
            'iEncabPregId' => $request->iEncabPregId  ?? 0
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
            //Se cambio el nombre Sp_DEL_encabezado_pregunta
            $resp = DB::select('exec ere.SP_DEL_encabezadoPregunta @_iEncabPregId = ?', $params);
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

            $resp = DB::select('exec ere.SP_DEL_pregunta @_iPreguntaId = ?', $params);

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

        $preguntasDB = PreguntasRepository::obtenerBancoPreguntasByParams($params);

        $phpTemplateWord = new TemplateProcessor(storage_path() . DIRECTORY_SEPARATOR .  'template-ere.docx');

        $preguntasDB = PreguntasRepository::obtenerBancoPreguntasByParams($params);

        $phpTemplateWord->cloneBlock('block_preguntas', count($preguntasDB), true, true);

        $phpTemplateWord->setValue('cantidadPreguntas', (count($preguntasDB)));

        foreach ($preguntasDB as $indexPregunta => $pregunta) {
            $phpTemplateWord->setValue('index#' . ($indexPregunta + 1), $indexPregunta + 1);

            if (strpos($pregunta->cPregunta, ';base64,')) {
                preg_match('/<img src="(data:image\/[a-zA-Z0-9]+;base64,[^"]+)"/', $pregunta->cPregunta, $matches);

                $imagen = isset($matches[1]) ? $matches[1] : null;

                $phpTemplateWord->setImageValue('cPregunta#' . ($indexPregunta + 1), array('path' => $imagen, 'width' => 200, 'height' => 200, 'ratio' => false));
            } else {
                $phpTemplateWord->setValue('cPregunta#' . ($indexPregunta + 1), strip_tags($pregunta->cPregunta));
            }

            if (isset($pregunta->alternativas)) {
                $phpTemplateWord->cloneBlock('block_alternativas#' . ($indexPregunta + 1), count($pregunta->alternativas), true, true);

                foreach ($pregunta->alternativas as $indexAlternativa => $alternativa) {
                    // Reemplazar valores de las alternativas dinámicamente
                    $phpTemplateWord->setValue('cAlternativaLetra#' . ($indexPregunta + 1) . '#' . ($indexAlternativa + 1), $alternativa->cAlternativaLetra);
                    $phpTemplateWord->setValue('cAlternativaDescripcion#' . ($indexPregunta + 1) . '#' . ($indexAlternativa + 1), strip_tags($alternativa->cAlternativaDescripcion));
                }
            }
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $response->headers->set('Content-Disposition', 'attachment;filename="preguntas_generated.docx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        ob_start();
        $phpTemplateWord->saveAs('php://output');
        $content = ob_get_contents();
        ob_end_clean();

        $response->setContent($content);

        return $response;
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
    public function generarWordEvaluacionByIds(Request $request)
    {
        $params = [
            'BancoId' => $request->ids,
            'iDocenteId' => 1,
            'iCursoId' => $request->iCursoId,
        ];
        // Obtener las preguntas desde el repositorio
        $preguntasDB = PreguntasRepository::obtenerBancoPreguntas($params);

        // Verificar si se encontraron preguntas
        if (empty($preguntasDB)) {
            return response()->json(['error' => 'No se encontraron preguntas para los IDs proporcionados.'], 404);
        }

        $phpTemplateWord = new TemplateProcessor(storage_path('template-eva.docx'));

        // Asignar valores de Año y Curso
        $phpTemplateWord->setValue('curso', $request->curso ?? 'Curso no especificado');
        $phpTemplateWord->setValue('anio', $request->anio ?? date('Y'));

        // Clonar el bloque de preguntas
        $phpTemplateWord->cloneBlock('block_preguntas', count($preguntasDB), true, true);

        // Asignar el valor de cantidad de preguntas
        $phpTemplateWord->setValue('cantidadPreguntas', count($preguntasDB));

        foreach ($preguntasDB as $indexPregunta => $pregunta) {
            $indice = $indexPregunta + 1;
            $phpTemplateWord->setValue("index#$indice", $indice);

            // Manejo de la pregunta
            if (strpos($pregunta->cBancoPregunta, ';base64,')) {
                preg_match('/<img src="(data:image\/[a-zA-Z0-9]+;base64,[^"]+)"/', $pregunta->cBancoPregunta, $matches);
                $imagenBase64 = $matches[1] ?? null;

                if ($imagenBase64) {
                    // Decodificar y guardar la imagen temporalmente
                    $imagePath = storage_path("temp_image_$indice.png");
                    file_put_contents($imagePath, base64_decode(explode(';base64,', $imagenBase64)[1]));

                    $phpTemplateWord->setImageValue("cPregunta#$indice", [
                        'path' => $imagePath,
                        'width' => 200,
                        'height' => 200,
                        'ratio' => false
                    ]);

                    // Eliminar la imagen temporal
                    unlink($imagePath);
                } else {
                    $phpTemplateWord->setValue("cPregunta#$indice", 'Imagen no disponible');
                }
            } else {
                $phpTemplateWord->setValue("cPregunta#$indice", strip_tags($pregunta->cBancoPregunta));
            }

            // Manejo de las alternativas
            if (!empty($pregunta->alternativas)) {
                $phpTemplateWord->cloneBlock("block_alternativas#$indice", count($pregunta->alternativas), true, true);

                foreach ($pregunta->alternativas as $indexAlternativa => $alternativa) {
                    $altIndice = $indexAlternativa + 1;
                    $phpTemplateWord->setValue("cAlternativaLetra#$indice#$altIndice", $alternativa->cBancoAltLetra);
                    $phpTemplateWord->setValue("cAlternativaDescripcion#$indice#$altIndice", strip_tags($alternativa->cBancoAltDescripcion));
                }
            }
        }

        // Configurar respuesta HTTP para descarga
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $response->headers->set('Content-Disposition', 'attachment;filename="preguntas_generated.docx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        // Generar el archivo y enviar al navegador
        ob_start();
        $phpTemplateWord->saveAs('php://output');
        $content = ob_get_clean();
        $response->setContent($content);

        return $response;
    }
}
