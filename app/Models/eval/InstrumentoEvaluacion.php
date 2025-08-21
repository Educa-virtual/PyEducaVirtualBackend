<?php

namespace App\Models\eval;

use App\Helpers\VerifyHash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InstrumentoEvaluacion extends Model
{
    public function obtener(array $params)
    {
        $params = [
            $params['iInstrumentoId'] ?? 0,
            $params['iDocenteId'] ?? 0,
            $params['idDocCursoId'] ?? 0,
            $params['iCursoId'] ?? 0,
            $params['busqueda'] ?? '',
            $params['ínstrumentoIdSeleccionado'] ?? 0,
        ];

        $data = DB::select('exec eval.SP_SEL_instrumentoEvaluaciones
                @_iInstrumentoId = ?
                ,@_iDocenteId = ?
                ,@_idDocCursoId = ?
                ,@_iCursoId = ?
                ,@_busqueda = ?
                ,@_iInstrumentoIdSeleccionado = ?
            ', $params);

        foreach ($data as $key => $item) {
            $criterios = $item->criterios ?? '[]';
            $data[$key]->criterios  = json_decode($criterios, true);
        }

        return $data;
    }
    public function guardarInstrumentos($request){
        $iDocenteId = VerifyHash::decodes($request["iDocenteId"]);
        $idDocCursoId = $request->idDocCursoId;
        $iCursoId = $request->iCursoId;
        $cInstrumentoNombre = $request->cInstrumentoNombre;
        $cInstrumentoDescripcion = $request->cInstrumentoDescripcion;
        
        $solicitud = [
            $iDocenteId,
            $idDocCursoId,
            $iCursoId,
            $cInstrumentoNombre,
            $cInstrumentoDescripcion,
        ];
        $columnas = str_repeat('?,',count($solicitud)-1).'?';
        $data = DB::select("EXEC [doc].[SP_INS_instrumentos_Evaluacion] ".$columnas, $solicitud);
        return $data;
    }
    public function editarInstrumentos($request){
        $iInstrumentoId = $request->iInstrumentoId;
        $iDocenteId = VerifyHash::decodes($request->iDocenteId);
        $idDocCursoId = $request->idDocCursoId;
        $iCursoId = $request->iCursoId;
        $cInstrumentoNombre = $request->cInstrumentoNombre;
        $cInstrumentoDescripcion = $request->cInstrumentoDescripcion;
        
        $solicitud = [
            $iInstrumentoId,
            $iDocenteId,
            $idDocCursoId,
            $iCursoId,
            $cInstrumentoNombre,
            $cInstrumentoDescripcion,
        ];

        $columnas = str_repeat('?,',count($solicitud)-1).'?';
        $data = DB::select("EXEC [doc].[SP_UPD_instrumentos_Evaluacion] ".$columnas, $solicitud);
        return $data;
    }
}
