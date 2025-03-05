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
            ->select('iGrupoId', 'cGrupoNombre')
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
    
    public function obtenerDatosMiembros(Request $request){
        // mostrar datos de estudiantes para miembros de grupo
        $opcion = $request->opcion;
        $iIieeId = $request->iIieeId ?? NULL;
        $iYAcadId = $request->iYAcadId ?? NULL;
        $iSedeId = $request->iSedeId ?? NULL;
        
        //  la opcion 1 muestra los estudiantes de la institucion
        $solicitud = [
            $opcion,
            $iIieeId,
            $iYAcadId,
            $iSedeId,
        ];
        $query = 'EXEC acad.Sp_SEL_estudianteXdocenteXespecialista '.str_repeat('?,',count($solicitud)-1).'?';
        $data = DB::select($query, $solicitud);
        try {
            $data = DB::select($query, $solicitud);
            return ResponseHandler::success($data);
        } catch (Exception $e) {
            return ResponseHandler::error("Error para obtener Datos ",500,$e->getMessage());
        }
    }

    public function registrar(Request $request){

        $iPersId = $this->decodeValue($request->input('iPersId'));
        $listaGrupos = $request->input('listaGrupos'); 

        $solicitud = [
            $iPersId,                // ID de la persona
            $request->input('iTipoComId'),             // Tipo de comunicado
            $request->input('iPrioridadId'),           // Prioridad
            $request->input('cComunicadoTitulo'),      // Título
            $request->input('cComunicadoDescripcion'), // Descripción
            $request->input('dtComunicadoEmision'),    // Fecha de emisión
            $request->input('dtComunicadoHasta'),      // Fecha de caducidad
            null,                                      // URL (pendiente)
            $request->input('iEstado'),                // Estado (bComunicadoArchivado)
            null,                                      // bComunicadoUgeles (pendiente)
            null,                                      // bComunicadoIes (pendiente)
            null,                                      // bComunicadoPerfil (pendiente)
            null,                                      // iActTipoId (pendiente)
            null,                                      // iUgelId (pendiente)
            null,                                      // iGradoId (pendiente)
            null,                                      // iSemAcadId (pendiente)
            $request->input('iYAcadId'),               // Año académico
            null,                                      // iSeccionId (pendiente)
            null,                                      // iCursoId (pendiente)
            null,                                      // iSedeId (pendiente)
            null,                                      // iDocenteId (pendiente)
            null,                                      // iEstudianteId (pendiente)
            null                                       // iEspecialistaId (pendiente)
        ];

        $query = 'EXEC com.Sp_INS_comunicados '.str_repeat('?,',count($solicitud)-1).'?';

        try {
            $data = DB::select($query, $solicitud);

            $iComunicadoId = $data[0]->resultado ?? null;
            
            if (!$iComunicadoId) {
                return ResponseHandler::error("No se pudo obtener el ID del comunicado", 500);
            }

            //  Insertar los grupos en com.destinos_grupos
            if (!empty($listaGrupos)) {
                foreach ($listaGrupos as $iGrupoId) {
                    DB::table('com.destinos_grupos')->insert([
                        'iGrupoId' => $iGrupoId,
                        'iComunicadoId' => $iComunicadoId
                    ]);
                }
            }

            return ResponseHandler::success(['iComunicadoId' => $iComunicadoId]);

        } catch (Exception $e) {
            return ResponseHandler::error("Error al registrar comunicado ",500,$e->getMessage());
        }

    }
}
