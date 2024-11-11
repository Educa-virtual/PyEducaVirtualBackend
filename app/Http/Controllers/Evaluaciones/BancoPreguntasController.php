<?php

namespace App\Http\Controllers\Evaluaciones;

use App\Http\Controllers\ApiController;
use App\Models\aula\Evaluacion;
use App\Repositories\aula\ProgramacionActividadesRepository;
use App\Repositories\Evaluaciones\BancoRepository;
use App\Repositories\Evaluaciones\PreguntasEvaluacionRepository;
use App\Repositories\PreguntasRepository;
use DateTime;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class BancoPreguntasController extends ApiController
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function obtenerBancoPreguntas(Request $request)
    {

        $params = [
            'iCursoId' => $this->decodeId($request->iCursoId ?? 0),
            'iDocenteId' => $this->decodeId($request->iDocenteId ?? 0),
            'iCurrContId' => $request->iCurrContId,
            'iNivelCicloId' => $request->iNivelCicloId,
            'busqueda' => $request->busqueda ?? '',
            'iTipoPregId' => $request->iTipoPregId ?? 0,
            'idEncabPregId' => $request->iEncabPregId ?? 0
        ];

        try {
            $resp = BancoRepository::obtenerPreguntas($params);
            return $this->successResponse($resp, 'Datos obtenidos correctamente');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Error al obtener los datos');
        }
    }

    public function guardarActualizarPreguntaConAlternativas(Request $request)
    {

        DB::beginTransaction();
        $iEncabPregId = (int) $request->encabezado['iEncabPregId'];
        if ($iEncabPregId === -1) {
            $iEncabPregId = null;
        } else {
            $paramsEncabezado = [
                'idEncabPregId' => $iEncabPregId,
                'cEncabPregTitulo' => $request->encabezado['cEncabPregTitulo'],
                'cEncabPregContenido' => $request->encabezado['cEncabPregContenido'],
                'iCursoId' => $this->decodeId($request->iCursoId),
                'iNivelCicloId' => $this->decodeId($request->iNivelCicloId),
                'iDocenteId' => $this->decodeId($request->iDocenteId),
            ];
            try {
                $resp =  PreguntasEvaluacionRepository::guardarActualizarPreguntaEncabezado($paramsEncabezado);
                if ($iEncabPregId == 0) {
                    $iEncabPregId = $resp->id;
                }
            } catch (Throwable $e) {
                DB::rollBack();
                $message = $this->handleAndLogError($e,  'Error al guardar el encabezado');
                return $this->errorResponse(null, $message);
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

            $iPreguntaId = $pregunta['isLocal'] ?? false ? 0 : (int) $pregunta['iPreguntaId'];
            $params = [
                'iBancoId' => $iPreguntaId,
                'iDocenteId' => $this->decodeId($request->iDocenteId),
                'iTipoPregId' => $pregunta['iTipoPregId'],
                'iCurrContId' => $this->decodeId($request->iCurrContId),
                // 'dtBancoCreacion' => $request->,
                'cBancoPregunta' => $pregunta['cPregunta'],
                'dtBancoTiempo' => $fechaConHora,
                'cBancoTextoAyuda' => $pregunta['cPreguntaTextoAyuda'] ?? '',
                'nBancoPuntaje' => $pregunta['iPreguntaPeso'],
                'idEncabPregId' => $iEncabPregId,
                'iCursoId' => $this->decodeId($request->iCursoId),
                'iNivelCicloId' => $this->decodeId($request->iNivelCicloId),
            ];
            // pregunta
            $respPregunta = null;
            try {
                $respPregunta = BancoRepository::guardarActualizarPregunta($params);
                $respPregunta = $respPregunta[0];
                $preguntas[$key]['iPreguntaId'] = $respPregunta->id;
            } catch (Throwable $e) {
                DB::rollBack();
                $message = $this->handleAndLogError($e,  'Error al guardar los datos');
                return $this->errorResponse(null, $message);
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
                    $respAlt = DB::select('exec eval.SP_DEL_alternativaPregunta @_iBancoAltId = ?', $paramsAlternativaEliminar);
                } catch (Throwable $e) {
                    DB::rollBack();
                    $defaultMessage = $this->handleAndLogError($e, 'Error al eliminar');
                    return $this->errorResponse($e, $defaultMessage);
                }
            }

            // guardar actualizar alternativas
            foreach ($alternativasActualizar as $altKey => $alternativa) {

                try {
                    $paramsAlternativa = [
                        'iBancoAltId' =>  $alternativa['isLocal'] ?? false ? 0 : (int) $alternativa['iAlternativaId'],
                        'iBancoId' =>  (int) $respPregunta->id,
                        'cBancoAltLtera' =>  $alternativa['cAlternativaLetra'],
                        'cBancoAltDescripcion' =>  $alternativa['cAlternativaDescripcion'],
                        'bBancoAltRptaCarrecta' =>  $alternativa['bAlternativaCorrecta'] ? 1 : 0,
                        'cBancoAltExplicacionRpta' =>  $alternativa['cAlternativaExplicacion'] ?? ''
                    ];
                    $respAlt  = BancoRepository::guardarActualizarAlternativa($paramsAlternativa);
                    $respAlt = $respAlt[0];
                    $preguntas[$key]['alternativas'][$altKey]['iAlternativaId'] = $respAlt->id;
                } catch (Throwable $e) {
                    DB::rollBack();
                    $message = $this->handleAndLogError($e, 'Error al guardar los cambios de la alternativa');
                    return $this->errorResponse(null, $message);
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
                    $resp = DB::select('exec eval.SP_DEL_alternativaPregunta  @_iBancoAltId', $paramsAlternativaEliminar);

                    $resp = $resp[0];
                } catch (Throwable $e) {
                    DB::rollBack();
                    $defaultMessage = $this->handleAndLogError($e, 'Error al eliminar');
                    return $this->errorResponse(null, $defaultMessage);
                }
            }

            try {

                $resp = DB::select('exec eval.SP_DEL_pregunta @_iBancoId = ?', [$pregunta['iPreguntaId']]);

                if (count($resp) === 0) {
                    return $this->errorResponse($resp, 'Error al eliminar la pregunta.');
                }

                $resp = $resp[0];
            } catch (Throwable $e) {
                DB::rollBack();
                $message = $this->handleAndLogError($e, 'Error al eliminar la pregunta');
                return $this->errorResponse(null, $message);
            }
        }

        // guarda las preguntas en la evaluacion si se envia el id
        $iEvaluacionId = (int) $request->iEvaluacionId  ?? 0;
        if ($iEvaluacionId !== 0) {
            try {
                $evaluacionPregunta = new Evaluacion();
                $preguntas = $evaluacionPregunta->guardarPreguntas(
                    $iEvaluacionId,
                    $preguntas
                );
            } catch (Throwable $e) {
                DB::rollBack();
                $message = $this->handleAndLogError($e, 'Error al guardar los datos');
                return $this->errorResponse(null, $message);
            }
        }

        // retornar preguntas con los ids y las alternativas en un array

        $preguntasIds =  array_map(function ($item) {
            return $item['iPreguntaId'];
        }, $preguntas);

        $ids = implode(',', $preguntasIds);
        $preguntasResponse = BancoRepository::obtenerPreguntas(['iBancoIds' => $ids]);

        DB::commit();

        return $this->successResponse($preguntasResponse, 'Cambios realizados correctamente');
    }

    public function obtenerEncabezadosPreguntas(Request $request)
    {
        $iDocenteId = $this->decodeId($request->iDocenteId);
        $iCursoId = $this->decodeId($request->iCursoId);
        $iNivelCicloId = $this->decodeId($request->iNivelCicloId);

        $params = [
            'iCursoId' => $iCursoId,
            'iNivelCicloId' => $iNivelCicloId,
            'iDocenteId' => $iDocenteId,
            'schema' => 'eval'
        ];
        try {
            $encabezados = PreguntasRepository::obtenerCabecerasPregunta($params);
            return $this->successResponse($encabezados, 'Datos obtenidos correctamente');
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al obetener los datos');
        }
    }

    public function eliminarBancoPreguntasById(Request $request, $id)
    {
        $params = [
            $id
        ];

        try {

            $resp = DB::select('exec eval.SP_DEL_pregunta @_iBancoId = ?', $params);

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
}
