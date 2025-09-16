<?php

namespace App\Http\Controllers\eval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\SimpleType\Jc;


class EvaluacionPreguntasController extends Controller
{
    public function guardarEvaluacionPreguntas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iEvaluacionId' => ['required'],
            'iDocenteId' => ['required'],
            'iTipoPregId' => ['required'],
            'cEvalPregPregunta' => ['required']
        ], [
            'iEvaluacionId.required' => 'No se encontró el identificador iEvaluacionId',
            'iDocenteId.required' => 'No se encontró el identificador iDocenteId',
            'iTipoPregId.required' => 'No se encontró el identificador iTipoPregId',
            'cEvalPregPregunta.required' => 'Debe ingresar el enunciado de la pregunta',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iEvaluacionId',
                'iDocenteId',
                'iTipoPregId',
                'iCursoId',
                'iNivelCicloId',
                'idEncabPregId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iEvaluacionId               ??  NULL,
                $request->iDocenteId                  ??  NULL,
                $request->iTipoPregId                 ??  NULL,
                $request->iCursoId                    ??  NULL,
                $request->iNivelCicloId               ??  NULL,
                $request->idEncabPregId               ??  NULL,
                $request->cEvalPregPregunta           ??  NULL,
                $request->cEvalPregTextoAyuda         ??  NULL,
                $request->jsonAlternativas            ??  NULL,
                $request->iCredId                     ??  NULL
            ];

            $data = DB::select(
                'exec eval.SP_INS_evaluacionPreguntas 
                    @_iEvaluacionId=?,   
                    @_iDocenteId=?,   
                    @_iTipoPregId=?,   
                    @_iCursoId=?,   
                    @_iNivelCicloId=?,   
                    @_idEncabPregId=?,   
                    @_cEvalPregPregunta=?,   
                    @_cEvalPregTextoAyuda=?,   
                    @_jsonAlternativas=?,   
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iEvalPregId > 0) {
                $message = 'Se ha guardado exitosamente';
                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => []],
                    Response::HTTP_OK
                );
            } else {
                $message = 'No se ha podido guardar';
                return new JsonResponse(
                    ['validated' => false, 'message' => $message, 'data' => []],
                    Response::HTTP_OK
                );
            }
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function obtenerEvaluacionPreguntasxiEvaluacionId(Request $request, $iEvaluacionId)
    {
        $request->merge(['iEvaluacionId' => $iEvaluacionId]);

        try {
            $fieldsToDecode = [
                'iEvaluacionId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iEvaluacionId    ??  NULL,
                $request->iCredId          ??  NULL
            ];

            $data = DB::select(
                'exec eval.SP_SEL_evaluacionPreguntasxiEvaluacionId
                    @_iEvaluacionId=?,   
                    @_iCredId=?',
                $parametros
            );

            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);

            return new JsonResponse(
                ['validated' => true, 'message' => 'Se ha obtenido exitosamente ', 'data' => $data],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
    public function actualizarEvaluacionPreguntasxiEvalPregId(Request $request, $iEvalPregId)
    {
        $request->merge(['iEvalPregId' => $iEvalPregId]);

        $validator = Validator::make($request->all(), [
            'iEvalPregId' => ['required'],
            'iTipoPregId' => ['required'],
            'cEvalPregPregunta' => ['required']
        ], [
            'iEvalPregId.required' => 'No se encontró el identificador iEvalPregId',
            'iTipoPregId.required' => 'No se encontró el identificador iTipoPregId',
            'cEvalPregPregunta.required' => 'Debe ingresar el enunciado de la pregunta',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iEvalPregId',
                'iTipoPregId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iEvalPregId                 ??  NULL,
                $request->iTipoPregId                 ??  NULL,
                $request->cEvalPregPregunta           ??  NULL,
                $request->cEvalPregTextoAyuda         ??  NULL,
                $request->jsonAlternativas            ??  NULL,
                $request->iCredId                     ??  NULL
            ];

            $data = DB::select(
                'exec eval.SP_UPD_evaluacionPreguntasxiEvalPregId 
                    @_iEvalPregId=?,   
                    @_iTipoPregId=?,   
                    @_cEvalPregPregunta=?,   
                    @_cEvalPregTextoAyuda=?,   
                    @_jsonAlternativas=?,   
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iEvalPregId > 0) {
                $message = 'Se ha actualizado exitosamente';
                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => []],
                    Response::HTTP_OK
                );
            } else {
                $message = 'No se ha podido actualizar';
                return new JsonResponse(
                    ['validated' => false, 'message' => $message, 'data' => []],
                    Response::HTTP_OK
                );
            }
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function eliminarEvaluacionPreguntasxiEvalPregId(Request $request, $iEvalPregId)
    {
        $request->merge(['iEvalPregId' => $iEvalPregId]);

        $validator = Validator::make($request->all(), [
            'iEvalPregId' => ['required'],
        ], [
            'iEvalPregId.required' => 'No se encontró el identificador iEvalPregId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iEvalPregId',
                'iCredId'
            ];

            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iEvalPregId      ??  NULL,
                $request->iCredId      ??  NULL
            ];
            $data = DB::select(
                'exec eval.SP_DEL_evaluacionPreguntasxiEvalPregId
                    @_iEvalPregId=?,    
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iEvalPregId > 0) {
                $message = 'Se ha eliminado exitosamente';
                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => []],
                    Response::HTTP_OK
                );
            } else {
                $message = 'No se ha podido eliminar';
                return new JsonResponse(
                    ['validated' => false, 'message' => $message, 'data' => []],
                    Response::HTTP_OK
                );
            }
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function obtenerEvaluacionPreguntasxiEvaluacionIdxiEstudianteId(Request $request, $iEvaluacionId, $iEstudianteId)
    {
        $request->merge(['iEvaluacionId' => $iEvaluacionId]);
        $request->merge(['iEstudianteId' => $iEstudianteId]);

        try {
            $fieldsToDecode = [
                'iEvaluacionId',
                'iEstudianteId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iEvaluacionId    ??  NULL,
                $request->iEstudianteId    ??  NULL,
                $request->iCredId          ??  NULL
            ];

            $data = DB::select(
                'exec eval.SP_SEL_evaluacionPreguntasxiEvaluacionIdxiEstudianteId
                    @_iEvaluacionId=?,   
                    @_iEstudianteId=?,   
                    @_iCredId=?',
                $parametros
            );

            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);

            return new JsonResponse(
                ['validated' => true, 'message' => 'Se ha obtenido exitosamente ', 'data' => $data],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function generarWordxiEvaluacionId(Request $request, $iEvaluacionId, $iCredId)
    {
        $request->merge([
            'iEvaluacionId' => $iEvaluacionId,
            'iCredId' => $iCredId,
        ]);

        try {
            $fieldsToDecode = ['iEvaluacionId', 'iCredId'];
            $request = VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [$request->iEvaluacionId ?? NULL, $request->iCredId ?? NULL];

            $data = DB::select(
                'exec eval.SP_SEL_evaluacionPreguntasxiEvaluacionId @_iEvaluacionId=?, @_iCredId=?',
                $parametros
            );

            if (empty($data)) {
                return response()->json(['error' => 'No se encontraron preguntas'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => substr($e->errorInfo[2] ?? '', 54)], 404);
        }

        $area_grado_nivel = DB::select("
        SELECT TOP 1 c.cCursoNombre,
        CONCAT(g.cGradoAbreviacion,' de ',nt.cNivelTipoNombre) AS cNivel
        FROM eval.evaluaciones AS e
        INNER JOIN aula.programacion_actividades AS pa ON pa.iProgActId = e.iProgActId
        INNER JOIN acad.docente_cursos AS dc ON dc.idDocCursoId = pa.idDocCursoId
        INNER JOIN acad.ies_cursos AS ic ON ic.iIeCursoId = dc.iIeCursoId
        INNER JOIN acad.cursos_niveles_grados AS cng ON cng.iCursosNivelGradId = ic.iCursosNivelGradId
        INNER JOIN acad.cursos AS c ON c.iCursoId = cng.iCursoId
        INNER JOIN acad.nivel_grados AS ng ON ng.iNivelGradoId = cng.iNivelGradoId
        INNER JOIN acad.grados AS g ON g.iGradoId = ng.iGradoId
        INNER JOIN acad.nivel_ciclos AS nc ON nc.iNivelCicloId = ng.iNivelCicloId
        INNER JOIN acad.nivel_tipos AS nt ON nt.iNivelTipoId = nc.iNivelTipoId
        WHERE e.iEvaluacionId = ?", [$request->iEvaluacionId]);

        $area = count($area_grado_nivel) ? $area_grado_nivel[0]->cCursoNombre : '-';
        $nivel = count($area_grado_nivel) ? $area_grado_nivel[0]->cNivel : '-';

        // 🔹 Instrucciones
        $instrucciones = [
            "A continuación, te presentamos preguntas que debes responder correctamente. La respuesta correcta se encuentra en una de las alternativas planteadas (si es que hubiera). Para ello:",
            "• LEE CADA PREGUNTA CON MUCHA ATENCIÓN.",
            "• RECUERDA LEER TODO LO QUE OBSERVAS, SUBRAYA, MARCA O SUMILLA, TODO LO QUE CONSIDERES NECESARIO.",
            "• SI ES NECESARIO, VUELVE A LEER LA PREGUNTA.",
            "• PIENSA BIEN ANTES DE MARCAR UNA RESPUESTA.",
            "• SOLAMENTE DEBES MARCAR UNA ALTERNATIVA POR CADA PREGUNTA.",
            "• MARCA TUS RESPUESTAS EN LA HOJA DE RESPUESTAS."
        ];

        $phpWord = new PhpWord();
        $section = $phpWord->addSection([
            'marginTop' => 1000,
            'marginBottom' => 1000,
            'marginLeft' => 1000,
            'marginRight' => 1000,
        ]);

        // 🔹 Carátula
        $section->addImage(public_path('images/logo-dremo.png'), [
            'width' => 150,
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
        ]);
        $section->addTextBreak(1);

        $section->addText('──────────────────────────────', ['color' => 'FF0000'], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addText("Año: " . date('Y'), ['size' => 10], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addTextBreak(1);

        $section->addText('PREGUNTAS DE EVALUACIÓN ' . $area, ['bold' => true, 'size' => 28, 'color' => 'FF0000'], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addTextBreak(1);

        $section->addText($nivel, ['size' => 16], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addTextBreak(3);

        $section->addText('INDICACIONES', ['bold' => true, 'size' => 20, 'color' => 'FF0000'], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addTextBreak(1);

        foreach ($instrucciones as $linea) {
            $section->addText($linea, ['size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
        }

        // 🔹 Salto de página antes de las preguntas
        $section->addPageBreak();

        // 🔹 Preguntas
        $contadorPregunta = 1;
        foreach ($data as $index => $pregunta) {
            // 🔹 Si la pregunta pertenece a un encabezado
            if (!empty($pregunta->idEncabPregId)) {
                // Título del encabezado (opcional)
                if (!empty($pregunta->cEncabPregTitulo)) {
                    $section->addText("PREGUNTA MÚLTIPLE: {$pregunta->cEncabPregTitulo}", ['bold' => true, 'size' => 14]);
                }

                // Obtenemos las preguntas anidadas en jsonPreguntas
                $preguntasEncab = json_decode($pregunta->jsonPreguntas ?? '[]', true);

                foreach ($preguntasEncab as $p) {
                    $contenido = $p['cEvalPregPregunta'] ?? '';
                    $contenido = preg_replace('/<img(.*?)>/i', '<img$1 />', $contenido);

                    Html::addHtml($section, "<strong>Pregunta $contadorPregunta:</strong> $contenido", false, false);

                    if (!empty($p['cEvalPregTextoAyuda'])) {
                        $section->addText(
                            $p['cEvalPregTextoAyuda'],
                            ['size' => 10, 'color' => '007BFF'],
                            [
                                'borderSize' => 6,
                                'borderColor' => '007BFF', // azul
                                'bgColor' => 'D9EDF7',     // fondo azul claro
                                'spaceAfter' => 200,
                                'spaceBefore' => 200,
                                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH,
                                'valign' => 'center',
                            ]
                        );
                    }

                    // Alternativas
                    if (!empty($p['iTipoPregId']) && in_array($p['iTipoPregId'], [1, 2])) {
                        $alternativas = $p['jsonAlternativas'] ?? [];

                        if (is_string($alternativas)) {
                            $alternativas = json_decode($alternativas, true);
                        }
                        foreach ($alternativas as $alt) {
                            $letra = $alt['cBancoAltLetra'] ?? '';
                            $desc  = $alt['cBancoAltDescripcion'] ?? '';
                            $htmlAlt = "<span>$letra) $desc</span>";

                            if (!empty($alt['cAlternativaImagen'])) {
                                $htmlAlt .= "<br>[Imagen]";
                            }

                            Html::addHtml($section, $htmlAlt, false, false);
                        }
                    }

                    $section->addTextBreak(1);
                    $contadorPregunta++;
                }
            } else {
                // 🔹 Pregunta normal (sin encabezado)
                $contenido = $pregunta->cEvalPregPregunta ?? '';
                $contenido = preg_replace('/<img(.*?)>/i', '<img$1 />', $contenido);

                Html::addHtml($section, "<strong>Pregunta $contadorPregunta:</strong> $contenido", false, false);

                if (!empty($pregunta->cEvalPregTextoAyuda)) {
                    $section->addText(
                        $pregunta->cEvalPregTextoAyuda,
                        ['size' => 10, 'color' => '007BFF'],
                        [
                            'borderSize' => 6,
                            'borderColor' => '007BFF', // azul
                            'bgColor' => 'D9EDF7',     // fondo azul claro
                            'spaceAfter' => 200,
                            'spaceBefore' => 200,
                            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH,
                            'valign' => 'center',
                        ]
                    );
                }

                // Alternativas
                if (!empty($pregunta->iTipoPregId) && in_array($pregunta->iTipoPregId, [1, 2])) {
                    $alternativas = json_decode($pregunta->jsonAlternativas ?? '[]', true);
                    foreach ($alternativas as $alt) {
                        $letra = $alt['cBancoAltLetra'] ?? '';
                        $desc  = $alt['cBancoAltDescripcion'] ?? '';
                        $htmlAlt = "<span>$letra) $desc</span>";

                        if (!empty($alt['cAlternativaImagen'])) {
                            $htmlAlt .= "<br>[Imagen]";
                        }

                        Html::addHtml($section, $htmlAlt, false, false);
                    }
                }

                $section->addTextBreak(1);
                $contadorPregunta++;
            }
        }

        // 🔹 Guardar y descargar
        $tempFile = tempnam(sys_get_temp_dir(), 'word');
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        return response()->download($tempFile, 'preguntas.docx')->deleteFileAfterSend(true);
    }
}
