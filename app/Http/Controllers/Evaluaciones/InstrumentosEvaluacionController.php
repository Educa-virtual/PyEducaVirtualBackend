<?php

namespace App\Http\Controllers\evaluaciones;

use App\DTO\WhereCondition;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Repositories\GeneralRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class InstrumentosEvaluacionController extends ApiController
{
    public function index(Request $request)
    {
        $params = [
            $request->iInstrumentoId ?? 0,
            $request->iDocenteId ?? 0,
            $request->idDocCursoId ?? 0,
            $request->iCursoId ?? 0,
            $request->busqueda ?? ''
        ];
        try {
            $resp = DB::select('exec eval.SEL_instrumento_evaluaciones
                @_iInstrumentoId = ?
                ,@_iDocenteId = ?
                ,@_idDocCursoId = ?
                ,@_iCursoId = ?
                ,@_busqueda = ?
            ', $params);
            return $this->successResponse($resp, 'Datos obtenidos correctamente');
        } catch (Exception $e) {
            $message = $this->handleAndLogError($e, 'Error al obtener los datos');
            return $this->errorResponse(null, $message);
        }
    }

    public function store(Request $request)
    {
        $iInstrumentoId = (int) $request->iInstrumentoId;
        $iSesionId = 1;
        $paramsInstrumentoToInsert = json_encode([
            'iDocenteId' => $request->iDocenteId,
            'idDocCursoId' => $request->idDocCursoId,
            'iCursoId' => $request->iCursoId,
            'cInstrumnetoNombre' => $request->cInstrumentoNombre,
            'cInstrumentoDescripcion' => $request->cInstrumentoDescripcion
        ]);
        DB::beginTransaction();
        if ($iInstrumentoId == 0) {
            try {
                $resp = GeneralRepository::insertar('eval', 'instrumento_evaluaciones', $paramsInstrumentoToInsert);
                $resp = $resp[0];
                $iInstrumentoId = $resp->id;
            } catch (Exception $e) {
                DB::rollBack();
                $message = $this->handleAndLogError($e, 'Error al actualizar los datos');
                return $this->errorResponse($e, $message);
            }
        } else {
            $paramsInstrumentoToUpdate = [
                'cInstrumentoNombre' => $request->cInstrumentoNombre,
                'cInstrumentoDescripcion' => $request->cInstrumentoDescripcion
            ];

            $whereToUpdate = json_encode([
                new WhereCondition('iInstrumentoId', $iInstrumentoId)
            ]);
            try {
                $resp = GeneralRepository::actualizar('eval', 'instrumento_evaluaciones', json_encode($paramsInstrumentoToUpdate), $whereToUpdate);
            } catch (Exception $e) {
                DB::rollBack();
                $message = $this->handleAndLogError($e, 'Error al actualizar los datos');
                return $this->errorResponse(null, $message);
            }
        }

        // criterios

        $criterios = $request->criterios;

        try {
            foreach ($criterios as $index => $criterio) {
                // insertar el criterio
                $iCriterioId = (int) $criterio['iCriterioId'];
                $niveles = $criterio['niveles'];
                if ($iCriterioId === 0) {
                    try {
                        $criterioToSave = json_encode([
                            'iInstrumentoId' => $criterio['iInstrumentoId'],
                            'cCriterioNombre' => $criterio['cCriterioNombre'],
                            'cCriteioDescripcion' => $criterio['cCriterioDescripcion'],
                            'iSesion' => $iSesionId
                        ]);
                        $resp = GeneralRepository::insertar('eval', 'criterio_evaluaciones', $criterioToSave);
                        $iCriterioId = $resp[0]->id;
                    } catch (Exception $e) {
                        $this->handleAndLogError($e);
                        throw new Exception("Error al crear el criterio: " . $criterio['cCriterioNombre']);
                    }
                } else {
                    // actualizar
                    try {
                        $criterioToUpdate = json_encode([
                            'cCriterioNombre' => $criterio['cCriterioNombre'],
                            'cCriteioDescripcion' => $criterio['cCriterioDescripcion'],
                            'iSesion' => $iSesionId
                        ]);
                        $criterioWhere = new WhereCondition('iCriterioId', $iCriterioId);
                        GeneralRepository::actualizar('eval', 'criterio_evaluaciones', $criterioToUpdate, $criterioWhere);
                    } catch (Exception $e) {
                        $this->handleAndLogError($e);
                        throw new Exception("Error al actualizar el criterio: " . $criterio['cCriterioNombre']);
                    }
                }

                // niveles
                foreach ($niveles as $nivel) {
                    $iNivelEvaId = (int) $nivel['iNivelEvaId'];
                    if ($iNivelEvaId === 0) {
                        // insertar nivel
                        $nivelToSave = json_encode([
                            'iCriterioId' => $iCriterioId,
                            'iEscalaCalifId' => $nivel['iEscalaCalifId'],
                            'cNivelEvaNombre' => $nivel['cNivelEvaNombre'],
                            'cNivelEvaDescripcion' => $nivel['cNivelEvaDescripcion'],
                            'iSesionId' => $iSesionId
                        ]);
                        try {
                            $resp =  GeneralRepository::insertar('eval', 'nivel_evaluaciones', $nivelToSave);
                            $iNivelEvaId = $resp[0]->id;
                        } catch (Exception $e) {
                            $this->handleAndLogError($e);
                            throw new Exception('Error al guardar el nivel: ' . $nivel['cNivelEvaNombre']);
                        }
                    } else {
                        // actualizar nivel
                        $nivelToUpdate = json_encode([
                            'iEscalaCalifId' => $nivel['iEscalaCalifId'],
                            'cNivelEvaNombre' => $nivel['cNivelEvaNombre'],
                            'cNivelEvaDescripcion' => $nivel['cNivelEvaDescripcion'],
                            'iSesionId' => $iSesionId
                        ]);
                        $whereNivel = json_encode([
                            new WhereCondition('iNivelEvaId', $iNivelEvaId)
                        ]);
                        try {
                            GeneralRepository::actualizar('eval', 'nivel_evaluaciones', $nivelToUpdate, $whereNivel);
                        } catch (Exception $e) {
                            $this->handleAndLogError($e);
                            throw new Exception("Error al actualizar el nivel: " . $criterio['cCriterioNombre']);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            $error = $this->handleAndLogError($e, 'Error al guardar los cambios');
            return $this->errorResponse(null, $error);
        }

        DB::commit();

        return $this->successResponse(null, 'Cambios realizados correctamente');
    }
}
