<?php

namespace App\Http\Controllers\Evaluaciones;

use App\Http\Controllers\ApiController;
use App\Repositories\aula\ProgramacionActividadesRepository;
use App\Repositories\Evaluaciones\BancoRepository;
use App\Repositories\Evaluaciones\PreguntasEvaluacionRepository;
use App\Repositories\PreguntasRepository;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class BancoPreguntasController extends ApiController
{

    public function obtenerBancoPreguntas(Request $request)
    {

        $params = [
            'iCursoId' => $request->iCursoId,
            'iDocenteId' => $request->iDocenteId,
            'iCurrContId' => $request->iCurrContId,
            'iNivelCicloId' => $request->iNivelCicloId,
            'busqueda' => $request->busqueda ?? '',
            'iTipoPregId' => $request->iTipoPregId ?? 0
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
                'idEncabPregId' => (int) $request->encabezado['iEncabPregId'],
                'cEncabPregTitulo' => $request->encabezado['cEncabPregTitulo'],
                'cEncabPregContenido' => $request->encabezado['cEncabPregContenido'],
                'iCursoId' => $request->iCursoId,
                'iNivelCicloId' => $request->iNivelCicloId,
                'iDocenteId' => $request->iDocenteId,
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
                'iDocenteId' => $request->iDocenteId,
                'iTipoPregId' => $pregunta['iTipoPregId'],
                'iCurrContId' => $request->iCurrContId,
                // 'dtBancoCreacion' => $request->,
                'cBancoPregunta' => $pregunta['cPregunta'],
                'dtBancoTiempo' => $fechaConHora,
                'cBancoTextoAyuda' => $pregunta['cPreguntaTextoAyuda'] ?? '',
                'nBancoPuntaje' => $pregunta['iPreguntaPeso'],
                'idEncabPregId' => $iEncabPregId,
                'iCursoId' => $request->iCursoId,
                'iNivelCicloId' => $request->iNivelCicloId,
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
                    $respAlt = DB::select('exec eval.Sp_DEL_alternativa_pregunta @_iBancoAltId = ?', $paramsAlternativaEliminar);
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
                    $resp = DB::select('exec eval.Sp_DEL_alternativa_pregunta  @_iBancoAltId', $paramsAlternativaEliminar);

                    $resp = $resp[0];
                } catch (Throwable $e) {
                    DB::rollBack();
                    $defaultMessage = $this->handleAndLogError($e, 'Error al eliminar');
                    return $this->errorResponse(null, $defaultMessage);
                }
            }

            try {

                $resp = DB::select('exec eval.Sp_DEL_pregunta @_iBancoId = ?', [$pregunta['iPreguntaId']]);

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
        DB::commit();

        // retornar preguntas con los ids y las alternativas en un array

        $preguntasIds =  array_map(function ($item) {
            return $item['iPreguntaId'];
        }, $preguntas);

        $ids = implode(',', $preguntasIds);
        $preguntasResponse = BancoRepository::obtenerPreguntas(['iBancoIds' => $ids]);

        return $this->successResponse($preguntasResponse, 'Cambios realizados correctamente');
    }

    public function obtenerEncabezadosPreguntas(Request $request)
    {
        $params = [
            'iCursoId' => $request['iCursoId'],
            'iNivelCicloId' => $request['iNivelCicloId'],
            'iDocenteId' => $request['iDocenteId'],
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

            $resp = DB::select('exec eval.Sp_DEL_pregunta @_iBancoId = ?', $params);

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
