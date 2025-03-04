<?php

namespace App\Http\Controllers\com;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ResponseHandler;
use Hashids\Hashids;

class ComunicadosController extends Controller
{
    private $hashids;
    public function __construct()
    {
        $this->hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
    }

    private function decodeValue($value)
    {
        if (is_null($value)) {
            return null;
        }
        return is_numeric($value) ? $value : ($this->hashids->decode($value)[0] ?? null);
    }

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

    public function obtenerDatos(Request $request){
        // Obtiene los tipos de comunicado
        $tipo_comunicado = DB::table('com.tipos_comunicados')
            ->select('iTipoComId', 'cTipoComNombre')
            ->get();
    
        // Obtiene los tipos de prioridad
        $tipo_prioridad = DB::table('com.prioridades')
            ->select('iPrioridadId', 'cPrioridadNombre')
            ->get();

        $iPersId = $this->decodeValue($request->input('iPersId')); 
        $grupos = DB::table('com.grupos')
            ->select('iPersId', 'cGrupoNombre')
            ->where('iPersId', $iPersId)
            ->get();
        
        // Obtiene el año académico desde el request
        $year_id = $request->input('year');
    
        // Obtiene el semestre académico basado en el año recibido
        $semestre_acad_id = null;
        $semestre = DB::select('SELECT iSemAcadId FROM acad.semestre_academicos WHERE iYAcadId = ?', [$year_id]);
    
        if (!empty($semestre)) {
            $semestre_acad_id = $semestre[0]->iSemAcadId;
        }
    
        // Retorna la respuesta dentro de 'data'
        return response()->json([
            'data' => [
                'tipo_comunicado' => $tipo_comunicado,
                'tipo_prioridad' => $tipo_prioridad,
                'semestre_acad_id' => $semestre_acad_id,
                'grupos' => $grupos,
            ]
        ]);
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
