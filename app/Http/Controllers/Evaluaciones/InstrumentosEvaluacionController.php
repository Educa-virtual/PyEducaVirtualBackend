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
                DB::enableQueryLog();
                $resp = GeneralRepository::insertar('eval', 'instrumento_evaluaciones', $paramsInstrumentoToInsert);
                // return $resp;
                $resp = $resp[0];
                $iInstrumentoId = $resp->id;
            } catch (Throwable $e) {
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

        // $criterios = $request->criterios;
        // try {
        //     $resp = DB::select('exec ');
        // } catch (Exception $e) {
        //     $message = $this->handleAndLogError($e, 'Error al actualizar los datos');
        //     return $this->errorResponse(null, $message);
        // }

        return $this->successResponse(null, 'Cambios realizados correctamente');
    }
}
