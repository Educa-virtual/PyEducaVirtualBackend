<?php

namespace App\Http\Controllers\evaluaciones;

use App\DTO\WhereCondition;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Repositories\GeneralRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstrumentosEvaluacionController extends ApiController
{
    public function index(Request $request)
    {
        $params = [];
        try {
            $resp = DB::select('exec ', $params);
            return $this->successResponse($resp, 'Datos obtenidos correctamente');
        } catch (Exception $e) {
            $message = $this->handleAndLogError($e, 'Error al obtener los datos');
            return $this->errorResponse(null, $message);
        }
    }

    public function store(Request $request)
    {
        $iInstrumentoId = (int) $request->iInstrumentoId;
        $paramsInstrumentoToInsert = [
            $request->iDocenteId,
            $request->idDocCursoId,
            $request->iCursoId,
            $request->cInstrumentoNombre,
            $request->cInstrumentoDescripcion
        ];
        DB::beginTransaction();
        if ($iInstrumentoId == 0) {
            try {
                $resp = GeneralRepository::insertar('eval', 'instrumento_evaluaciones', json_encode($paramsInstrumentoToInsert));
                $resp = $resp[0];
                $iInstrumentoId = $resp->id;
            } catch (Exception $e) {
                DB::rollBack();
                $message = $this->handleAndLogError($e, 'Error al actualizar los datos');
                return $this->errorResponse(null, $message);
            }
        } else {
            $paramsInstrumentoToUpdate = [
                $request->cInstrumentoNombre,
                $request->cInstrumentoDescripcion
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
            $resp = DB::select('exec ');
        } catch (Exception $e) {
            $message = $this->handleAndLogError($e, 'Error al actualizar los datos');
            return $this->errorResponse(null, $message);
        }
    }
}
