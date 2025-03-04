<?php

namespace App\Http\Controllers\com;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ResponseHandler;

class ComunicadosController extends Controller
{
    public function mostrar(Request $request){
        $opcion = $request->opcion;
        
        $solicitud = [
            $opcion,
        ];
    
        try {
            $data = DB::select('EXEC com.Sp_SEL_comunicados ?', $solicitud);
            ResponseHandler::success($data);
        } catch (Exception $e) {
            ResponseHandler::error("Error para obtener Datos ",500,$e->getMessage());
        }
    }
    public function registrar(Request $request){
        
        $solicitud = [
            1,                    //iPersId,
            1,                    //iTipoComId,
            1,                    //iPrioridadId,
            'titulo 1',                    //cComunicadoTitulo,
            'descricion 1',                    //cComunicadoDescripcion,
            null,                    //dtComunicadoEmision,
            null,                    //dtComunicadoHasta,
            null,                    //cComunicadoUrl,
            null,                    //bComunicadoArchivado,
            null,                    //bComunicadoUgeles,
            null,                    //bComunicadoIes,
            null,                    //bComunicadoPerfil,
            null,                    //iActTipoId,
            null,                    //iUgelId,
            null,                    //iGradoId,
            null,                    //iSemAcadId,
            1,                    //iYAcadId,
            null,                    //iSeccionId,
            null,                    //iCursoId,
            null,                    //iSedeId,
            null,                    //iDocenteId,
            null,                    //iEstudianteId,
            null,                    //iEspecialistaId
        ];

        $query = 'EXEC com.Sp_INS_comunicados '.str_repeat('?,',count($solicitud)-1).'?';
        $data = DB::select($query, $solicitud);

        try {
            $data = DB::select($query, $solicitud);
            return ResponseHandler::success($data);
        } catch (Exception $e) {
            return ResponseHandler::error("Error para obtener Datos ",500,$e->getMessage());
        }

    }
}
