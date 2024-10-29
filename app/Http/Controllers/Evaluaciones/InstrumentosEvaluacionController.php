<?php

namespace App\Http\Controllers\evaluaciones;

use App\DTO\WhereCondition;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Repositories\GeneralRepository;
use Carbon\Carbon;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class InstrumentosEvaluacionController extends ApiController
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function index(Request $request)
    {
        $iDocenteId = $this->decodeId($request->iDocenteId ?? 0);
        $idDocCursoId = $this->decodeId($request->idDocCursoId ?? 0);
        $iCursoId = $this->decodeId($request->iCursoId ?? 0);

        $params = [
            $request->iInstrumentoId ?? 0,
            $iDocenteId,
            $idDocCursoId,
            $iCursoId,
            $request->busqueda ?? ''
        ];
        try {
            $data = DB::select('exec eval.SEL_instrumento_evaluaciones
                @_iInstrumentoId = ?
                ,@_iDocenteId = ?
                ,@_idDocCursoId = ?
                ,@_iCursoId = ?
                ,@_busqueda = ?
            ', $params);
            foreach ($data as $key => $item) {
                $criterios = $item->criterios ?? '[]';
                $data[$key]->criterios  = json_decode($criterios, true);
            }
            return $this->successResponse($data, 'Datos obtenidos correctamente');
        } catch (Exception $e) {
            $message = $this->handleAndLogError($e, 'Error al obtener los datos');
            return $this->errorResponse(null, $message);
        }
    }

    public function store(Request $request)
    {
        $iInstrumentoId = (int) $request->iInstrumentoId;
        $iDocenteId = $this->decodeId($request->iDocenteId ?? 0);
        $idDocCursoId = $this->decodeId($request->idDocCursoId ?? 0);
        $iCursoId = $this->decodeId($request->iCursoId ?? 0);
        $iSesionId = 1;
        DB::beginTransaction();
        if ($iInstrumentoId == 0) {
            $paramsInstrumentoToInsert = json_encode([
                'iDocenteId' => $iDocenteId,
                'idDocCursoId' => $idDocCursoId,
                'iCursoId' => $iCursoId,
                'cInstrumentoNombre' => $request->cInstrumentoNombre,
                'cInstrumentoDescripcion' => $request->cInstrumentoDescripcion,
                'dtInstrumentoCreacion' =>  $this->getDateToDB(),
                'iEstado' => 1,
                'iSesionId' => $iSesionId
            ]);

            try {
                $resp = GeneralRepository::insertar('eval', 'instrumento_evaluaciones', $paramsInstrumentoToInsert);
                $resp = $resp[0];
                $iInstrumentoId = $resp->id;
            } catch (Exception $e) {
                DB::rollBack();
                $message = $this->handleAndLogError($e, 'Error al guardar los cambios');
                return $this->errorResponse(null, $message);
            }
        } else {
            $paramsInstrumentoToUpdate = json_encode([
                'cInstrumentoNombre' => $request->cInstrumentoNombre,
                'cInstrumentoDescripcion' => $request->cInstrumentoDescripcion,
                'dtActualizado' => $this->getDateToDB()
            ]);

            $whereToUpdate = json_encode([
                new WhereCondition('iInstrumentoId', $iInstrumentoId)
            ]);
            try {
                $resp = GeneralRepository::actualizar('eval', 'instrumento_evaluaciones', $paramsInstrumentoToUpdate, $whereToUpdate);
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
                            'iInstrumentoId' => $iInstrumentoId,
                            'cCriterioNombre' => $criterio['cCriterioNombre'],
                            'cCriterioDescripcion' => $criterio['cCriterioDescripcion'],
                            'iSesion' => $iSesionId,
                            'dtActualizado' => $this->getDateToDB()
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
                            'cCriterioDescripcion' => $criterio['cCriterioDescripcion'],
                            'iSesion' => $iSesionId,
                            'dtActualizado' => $this->getDateToDB()
                        ]);
                        DB::rollBack();
                        $criterioWhere = json_encode([
                            new WhereCondition('iCriterioId', $iCriterioId)
                        ]);
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
                            'iInstrumentoId' => $iInstrumentoId,
                            'iCriterioId' => $iCriterioId,
                            'iEscalaCalifId' => $nivel['iEscalaCalifId'],
                            'cNivelEvaNombre' => $nivel['cNivelEvaNombre'],
                            'iNivelEvaValor' => $nivel['iNivelEvaValor'],
                            'cNivelEvaDescripcion' => $nivel['cNivelEvaDescripcion'],
                            'iSesionId' => $iSesionId,
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
                            'iNivelEvaValor' => $nivel['iNivelEvaValor'],
                            'iSesionId' => $iSesionId,
                            'dtActualizado' => $this->getDateToDB()
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

    final public function destroy(Request $request, $id)
    {
        $cTipo = $request->cTipo;
        try {
            $resp = DB::select('exec eval.Sp_DEL_instrumento_evaluacion_rubrica_id 
                @_id = ?, @_cTipo = ?', [$id, $cTipo]);
            return $this->successResponse(null, 'Eliminado correctamente');
        } catch (Exception $e) {
            $message = $this->handleAndLogError($e, 'Error al eliminar la rúbrica');
            return $this->errorResponse(null, $message);
        }
    }
}
