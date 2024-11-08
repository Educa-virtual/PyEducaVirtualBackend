<?php

namespace App\Http\Controllers\Evaluaciones;

use App\DTO\WhereCondition;
use App\Http\Controllers\ApiController;
use App\Models\eval\BancoPreguntas;
use App\Models\eval\NivelLogroAlcanzadoEvaluacion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluacionEstudiantesController extends ApiController
{
    public function index(Request $request)
    {
        $iEvaluacionId = $this->decodeId($request->iEvaluacionId);
        try {
            $data = DB::select('exec eval.SP_SEL_estudiantesEvaluacion @_iEvaluacionId = ? ', [$iEvaluacionId]);
            return $this->successResponse($data, 'Datos obtenidos correctamente');
        } catch (Exception $e) {
            $message = $this->handleAndLogError($e, 'Error al obtener los datos');
            return $this->errorResponse(null, $message);
        }
    }

    public function obtenerEvaluacionRespuestasEstudiante(Request $request)
    {
        $iEstudianteId = (int) $this->decodeId($request->iEstudianteId);
        $iEvaluacionId = (int) $this->decodeId($request->iEvaluacionId);

        try {
            $data = DB::select(
                'exec eval.SP_SEL_examenEstudianteEvaluacionDocente
                    @_iEstudianteId = ?
                    ,@_iEvaluacionId = ?
                ',
                [$iEstudianteId, $iEvaluacionId]
            );
            $preguntas = (new BancoPreguntas())->procesarPreguntas($data);
            return $this->successResponse($preguntas, 'Datos obtenidos correctamente');
        } catch (Exception $e) {
            $mensaje = $this->handleAndLogError($e, 'Error al obtener los datos');
            return $this->errorResponse(null, $mensaje);
        }
    }

    public function calificarLogros(Request $request)
    {
        $logros = $request->logrosCalificacion;
        $ixColumn = $request->ixColumn ?? 'iNivelLogroEvaId';

        DB::beginTransaction();
        try {
            foreach ($logros as &$logro) {
                $iNivelLogroAlcId = $this->decodeId($logro['iNivelLogroAlcId'] ?? 0);
                $datosBase = [
                    'cNivelLogroAlcConclusionDescriptiva' => $logro['cNivelLogroAlcConclusionDescriptiva'],
                    'nNnivelLogroAlcNota' => $logro['nNnivelLogroAlcNota'],
                    'iEscalaCalifId' => $logro['iEscalaCalifId'],
                ];

                $datosInsertar = $datosBase;
                $datosInsertar['iEvalRptaId'] = $logro['iEvalRptaId'];
                $datosInsertar[$ixColumn] = $logro[$ixColumn];

                $nivelLogroAlcanzado = new NivelLogroAlcanzadoEvaluacion();
                if ($logro['iNivelLogroAlcId'] == 0) {
                    $resp = $nivelLogroAlcanzado->guardar(json_encode($datosInsertar));
                    $logro['newId'] = $resp[0]->id;
                } else {
                    $where = json_encode([
                        new WhereCondition('iNivelLogroAlcId', $iNivelLogroAlcId)
                    ]);

                    $nivelLogroAlcanzado->actualizar(json_encode($datosBase), $where);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            $mensaje =  $this->handleAndLogError($e, 'Error al guardar los cambios');
            return $this->errorResponse(null, $mensaje);
        } finally {
            unset($logro);
        }

        DB::commit();
        return $this->successResponse($logros, 'Cambios realizados correctamente');
    }
}
