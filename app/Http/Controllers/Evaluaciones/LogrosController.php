<?php

namespace App\Http\Controllers\Evaluaciones;

use App\DTO\WhereCondition;
use App\Http\Controllers\ApiController;
use App\Repositories\GeneralRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogrosController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $params = [
            $request->iEvalPregId,
        ];
        try {
            $logros = DB::select('exec eval.SP_SEL_preguntaLogros
                @_iEvalPregId = ?
            ', $params);
            return $this->successResponse($logros, 'Datos obtenidos correctamente');
        } catch (Exception $e) {
            $message = $this->handleAndLogError($e, 'Error al obtener los datos');
            return $this->errorResponse(null, $message);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $iNivelLogroEvaId  = $this->decodeId($request->iNivelLogroEvaId ?? 0);
        $paramsToSave = json_encode([
            'cNivelLogroEvaDescripcion' => $request->cNivelLogroEvaDescripcion,
            'iEvalPregId' => $this->decodeId($request->iEvalPregId ?? 0),
        ]);

        $paramsToUpdate = json_encode([
            'cNivelLogroEvaDescripcion' => $request->cNivelLogroEvaDescripcion,
        ]);

        $whereJson = json_encode([
            new WhereCondition('iNivelLogroEvaId', $iNivelLogroEvaId)
        ]);

        try {

            if ($iNivelLogroEvaId === 0) {
                $resp = GeneralRepository::insertar('eval', 'nivel_logro_evaluaciones', $paramsToSave);
                $iNivelLogroEvaId = $resp[0]->id;
            } else {
                GeneralRepository::actualizar('eval', 'nivel_logro_evaluaciones', $paramsToUpdate, $whereJson);
            }

            return $this->successResponse(['id' => $iNivelLogroEvaId], 'Cambios realizados correctamente');
        } catch (Exception $e) {
            $message = $this->handleAndLogError($e, 'Error al guardar los cambios.');
            return $this->errorResponse(null, $message);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $resp = DB::select('
                exec eval.SP_DEL_nivelLogroXevaluacionesId 
                @_iNivelLogroEvaId = ?    
            ', [$id]);
            return $this->successResponse($resp, 'Eliminado correctamente');
        } catch (Exception $e) {
            $message = $this->handleAndLogError($e, 'Error al obtener eliminar el logro');
            return $this->errorResponse(null, $message);
        }
    }
}
