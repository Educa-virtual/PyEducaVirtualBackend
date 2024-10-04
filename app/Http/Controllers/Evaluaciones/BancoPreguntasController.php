<?php

namespace App\Http\Controllers\Evaluaciones;

use App\Http\Controllers\ApiController;
use App\Repositories\Evaluaciones\BancoRepository;
use App\Repositories\PreguntasRepository;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                'iEncabPregId' => (int) $request->encabezado['iEncabPregId'],
                'cEncabPregTitulo' => $request->encabezado['cEncabPregTitulo'],
                'cEncabPregContenido' => $request->encabezado['cEncabPregContenido'],
                'iCursoId' => 1,
                'iNivelGradoId' => 1,
                'iColumnValue' => 1,
                'cColumnName' => 'iDocenteId',
                'cSchemaName'  => 'eval'
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
                'iBancoId' => $iPreguntaId,
                'iDocenteId' => $request->iDocenteId,
                'iTipoPregId' => $request->iTipoPregId,
                'iCurrContId' => $request->iCurrContId,
                // 'dtBancoCreacion' => $request->,
                'cBancoPregunta' => $request->cPregunta,
                'dtBancoTiempo' => $fechaConHora,
                'cBancoTextoAyuda' => $request->cPreguntaTextoAyuda,
                'nBancoPuntaje' => $request->iPreguntaPeso,
                'idEncabPregId' => $iEncabPregId,
                'iCursoId' => $request->iCursoId,
                'iNivelCicloId' => $request->iNivelCicloId,
            ];


            // pregunta
            $respPregunta = null;
            try {
                $respPregunta = BancoRepository::guardarActualizarPregunta($params);
                $respPregunta = $respPregunta[0];
            } catch (Exception $e) {
                DB::rollBack();
                return $this->errorResponse($e->getMessage(), 'Error al guardar los datos');
            }

            //     // alternativas
            //     $alternativasActualizar  = $pregunta['alternativas'] ?? [];
            //     $alternativasEliminar   = $pregunta['alternativasEliminar'] ?? [];
            //     // eliminar alternativas
            //     foreach ($alternativasEliminar as $alternativa) {
            //         $paramsAlternativaEliminar = [
            //             $alternativa['iAlternativaId']
            //         ];
            //         try {
            //             $resp = DB::select('exec ere.Sp_DEL_alternativa_pregunta @_iAlternativaId = ?', $paramsAlternativaEliminar);

            //             // $resp = $resp[0];
            //         } catch (Exception $e) {
            //             DB::rollBack();
            //             $defaultMessage = $this->returnError($e, 'Error al eliminar');
            //             return $this->errorResponse($e, $defaultMessage);
            //         }
            //     }

            //     // guardar actualizar alternativas
            //     foreach ($alternativasActualizar as $alternativa) {

            //         try {
            //             $paramsAlternativa = [
            //                 $alternativa['isLocal'] ?? false ? 0 : (int) $alternativa['iAlternativaId'],
            //                 (int) $respPregunta->id,
            //                 $alternativa['cAlternativaDescripcion'],
            //                 $alternativa['cAlternativaLetra'],
            //                 $alternativa['bAlternativaCorrecta'] ? 1 : 0,
            //                 $alternativa['cAlternativaExplicacion'] ?? ''
            //             ];
            //             $resp = $this->alternativaPreguntaRespository->guardarActualizarAlternativa($paramsAlternativa);
            //         } catch (Exception $e) {
            //             DB::rollBack();
            //             $message = $this->returnError($e, 'Error al guardar los cambios de la alternativa');
            //             return $this->errorResponse($e->getMessage(), $message);
            //         }
            //     }
        }

        // // eliminar preguntas
        // foreach ($preguntasEliminar as $pregunta) {
        //     $alternativasEliminar = array_merge($pregunta['alternativas'], $pregunta['alternativasEliminadas'] ?? []);
        //     foreach ($alternativasEliminar as $alternativa) {
        //         $paramsAlternativaEliminar = [
        //             $alternativa['iAlternativaId']
        //         ];
        //         try {
        //             $resp = DB::select('exec ere.Sp_DEL_alternativa_pregunta @_iAlternativaId = ?', $paramsAlternativaEliminar);

        //             $resp = $resp[0];
        //         } catch (Exception $e) {
        //             DB::rollBack();
        //             $defaultMessage = $this->returnError($e, 'Error al eliminar');
        //             return $this->errorResponse($e, $defaultMessage);
        //         }
        //     }

        //     try {

        //         $resp = DB::select('exec ere.Sp_DEL_pregunta @_iPreguntaId = ?', [$pregunta['iPreguntaId']]);

        //         if (count($resp) === 0) {
        //             return $this->errorResponse($resp, 'Error al eliminar la pregunta.');
        //         }

        //         $resp = $resp[0];
        //     } catch (Exception $e) {
        //         DB::rollBack();
        //         $message = $this->returnError($e, 'Error al eliminar la pregunta');
        //         return $this->errorResponse($e, $message);
        //     }
        // }
        DB::commit();

        return $this->successResponse(null, 'Cambion realizados correctamente');
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
}
