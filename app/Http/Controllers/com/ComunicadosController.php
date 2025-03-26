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
        $iIieeId = $request->input('iIieeId');
        $iSedeId = $request->input('iSedeId');
        $iPerfilId = $request->input('iPerfilId');
        // Obtiene el semestre académico basado en el año recibido
        $semestre_acad_id = null;
        $semestre = DB::select('SELECT iSemAcadId FROM acad.semestre_academicos WHERE iYAcadId = ?', [$year_id]);
    
        if (!empty($semestre)) {
            $semestre_acad_id = $semestre[0]->iSemAcadId;
        }

        $iIieeId = $request->input('iIieeId');
        // Llamada al SP que retorna los datos para los dropdown de Curso, Sección y Grado
        if ($iPerfilId == 7) {
            try {
                $pdo = DB::getPdo();
                $stmt = $pdo->prepare('EXEC com.Sp_SEL_docentexcursoxgradoxseccion ?, ?, ?, ?');
                $stmt->execute([$iPersId, $year_id, $iSedeId, $iIieeId]);
                
                // Primer conjunto: Cursos
                $cursos = $stmt->fetchAll(\PDO::FETCH_OBJ);
                // Segundo conjunto: Secciones
                $stmt->nextRowset();
                $secciones = $stmt->fetchAll(\PDO::FETCH_OBJ);
                // Tercer conjunto: Grados
                $stmt->nextRowset();
                $grados = $stmt->fetchAll(\PDO::FETCH_OBJ);
            } catch (Exception $e) {
                $cursos = [];
                $secciones = [];
                $grados = [];
            }
        } else {
            try {
                $pdo = DB::getPdo();
                $stmt = $pdo->prepare('EXEC com.Sp_SEL_ObtenerGruposDestino ?, ?');
                $stmt->execute([$iIieeId, $year_id]);
                
                // Primer conjunto: Cursos
                $cursos = $stmt->fetchAll(\PDO::FETCH_OBJ);
                // Segundo conjunto: Secciones
                $stmt->nextRowset();
                $secciones = $stmt->fetchAll(\PDO::FETCH_OBJ);
                // Tercer conjunto: Grados
                $stmt->nextRowset();
                $grados = $stmt->fetchAll(\PDO::FETCH_OBJ);
            } catch (Exception $e) {
                $cursos = [];
                $secciones = [];
                $grados = [];
            }
        }    

        // Retorna la respuesta dentro de 'data'
        return response()->json([
            'data' => [
                'tipo_comunicado' => $tipo_comunicado,
                'tipo_prioridad' => $tipo_prioridad,
                'semestre_acad_id' => $semestre_acad_id,
                'grupos' => $grupos,
                'cursos' => $cursos,
                'secciones' => $secciones,
                'grados' => $grados,
            ]
        ]);
    }

    public function registrar(Request $request){

        $iPersId = $this->decodeValue($request->input('iPersId'));
        $listaGrupos = $request->input('listaGrupos');
        
        $iDestinatarioId = $request->input('iDestinatarioId');
        $iTipoPersona = $request->input('iTipoPersona'); // 1 => Estudiante, 2 => Docente
    
        $iEstudianteId = null;
        $iDocenteId = null;
        if ($iTipoPersona == 1) {
            $iEstudianteId = $iDestinatarioId;
        } elseif ($iTipoPersona == 2) {
            $iDocenteId = $iDestinatarioId;
        }

        $solicitud = [
            $iPersId,                                  // ID de la persona 1
            $request->input('iTipoComId'),             // Tipo de comunicado 2 
            $request->input('iPrioridadId'),           // Prioridad 3
            $request->input('cComunicadoTitulo'),      // Título 4 
            $request->input('cComunicadoDescripcion'), // Descripción 5
            $request->input('dtComunicadoEmision'),    // Fecha de emisión 6
            $request->input('dtComunicadoHasta'),      // Fecha de caducidad 7
            null,                                      // URL (pendiente) 8 
            $request->input('iEstado'),                // Estado (bComunicadoArchivado) 9
            null,                                      // bComunicadoUgeles (pendiente) 10
            null,                                      // bComunicadoIes (pendiente)  11
            null,                                      // bComunicadoPerfil (pendiente) 12
            null,                                      // iActTipoId (pendiente) 13
            null,                                      // iUgelId 14
            $request->input('grado'),                  // iGradoId (pendiente) 15
            $request->input('iSemAcadId'),             // iSemAcadId (pendiente) 16
            $request->input('iYAcadId'),               // Año académico 17
            $request->input('seccion'),                // iSeccionId (pendiente) 18
            $request->input('curso'),                  // iCursoId (pendiente) 19
            $request->input('iSedeId'),                // iSedeId (pendiente) 20
            null,                                      // iDocenteId (pendiente) 21
            null,                                       // iEstudianteId (pendiente) 22
            null,                                       // iEspecialistaId (pendiente) 23
            $request->input('iDestinatarioId'),         // IDestinatarioiD 24
            $request->input('InstitucionId')            // IDestinatarioiD 26
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
    
    public function obtenerComunicadosPersona(Request $request)
    {
        $iPersId = $this->decodeValue($request->input('iPersId'));
        try {
            // Llamada al SP con el iPersId
            $data = DB::select('EXEC com.Sp_SEL_ObtenerComunicadosPorPersona ?', [$iPersId]);
            return ResponseHandler::success($data);
        } catch (Exception $e) {
            return ResponseHandler::error("Error al obtener comunicados", 500, $e->getMessage());
        }
    }

    public function eliminar(Request $request) {
        $iComunicadoId = $request->input('iComunicadoId');
        try {
            // Actualizar el comunicado: marcar como inactivo (archivado)
            DB::table('com.comunicados')
                ->where('iComunicadoId', $iComunicadoId)
                ->update(['bComunicadoArchivado' => 0]);
            
            // Eliminar los registros de la relación en destinos_grupos
            DB::table('com.destinos_grupos')
                ->where('iComunicadoId', $iComunicadoId)
                ->delete();
            
            return ResponseHandler::success(['mensaje' => 'Comunicado eliminado (archivado) correctamente']);
        } catch(Exception $e) {
            return ResponseHandler::error("Error al eliminar comunicado", 500, $e->getMessage());
        }
    }
    public function actualizar(Request $request) {
        $iComunicadoId = $request->input('iComunicadoId');
        
        $iDestinatarioId = $request->input('iDestinatarioId');
        $iTipoPersona = $request->input('iTipoPersona'); // 1 => Estudiante, 2 => Docente

        $iEstudianteId = null;
        $iDocenteId = null;
        if ($iTipoPersona == 1) {
            $iEstudianteId = $iDestinatarioId;
        } elseif ($iTipoPersona == 2) {
            $iDocenteId = $iDestinatarioId;
        }

        // Datos para actualizar
        $updateData = [
            'iTipoComId' => $request->input('iTipoComId'),
            'iPrioridadId' => $request->input('iPrioridadId'),
            'cComunicadoTitulo' => $request->input('cComunicadoTitulo'),
            'cComunicadoDescripcion' => $request->input('cComunicadoDescripcion'),
            'dtComunicadoEmision' => $request->input('dtComunicadoEmision'),
            'dtComunicadoHasta' => $request->input('dtComunicadoHasta'),
            'bComunicadoArchivado' => $request->input('iEstado'),
            'iYAcadId' => $request->input('iYAcadId'),

            'iGradoId' => $request->input('grado'),      
            'iSeccionId' => $request->input('seccion'),  
            'iCursoId' => $request->input('curso'),      
            'iSedeId' => $request->input('iSedeId'),     
            'iDocenteId' => $iDocenteId,                 
            'iEstudianteId' => $iEstudianteId,           
            
        ];
    
        try {
            // Actualizar el comunicado en la tabla principal
            DB::table('com.comunicados')
                ->where('iComunicadoId', $iComunicadoId)
                ->update($updateData);
    
            // Actualizar los grupos: primero eliminar los existentes y luego reinsertar
            DB::table('com.destinos_grupos')
                ->where('iComunicadoId', $iComunicadoId)
                ->delete();
    
            $listaGrupos = $request->input('listaGrupos');
            if (!empty($listaGrupos)) {
                foreach ($listaGrupos as $iGrupoId) {
                    DB::table('com.destinos_grupos')->insert([
                        'iGrupoId' => $iGrupoId,
                        'iComunicadoId' => $iComunicadoId
                    ]);
                }
            }
    
            return ResponseHandler::success(['mensaje' => 'Comunicado actualizado']);
        } catch(Exception $e) {
            return ResponseHandler::error("Error al actualizar comunicado", 500, $e->getMessage());
        }
    }

    public function obtenerComunicadosDestino(Request $request)
    {

        $iPersId = $this->decodeValue($request->iPersId);
        $iYAcadId =  $request->iYAcadId;
        $perfil = $request->perfil;
        $iSedeId = $request->iSedeId;

        $solicitud = [
            $iPersId,
            $iYAcadId,
            $iSedeId,
            json_encode($perfil),
        ];
        
        try {
             // Llamada al SP que obtiene los comunicados destino
             $data = DB::select('EXEC com.Sp_SEL_ObtenerComunicadosDestinoPorPersona ?,?,?,?', $solicitud);
             return ResponseHandler::success($data);
        } catch (Exception $e) {
             return ResponseHandler::error("Error al obtener comunicados destino", 500, $e->getMessage());
        }
    }
    public function obtenerComunicadoPersonalizado(Request $request){

        $iPersId    = $this->decodeValue($request->input('iPersId'));
        $iPerfilId  = $request->input('iPerfilId');

        $solicitud = [
            $iPersId,
            $iPerfilId,
        ];

        try {
            // Llamada al SP que obtiene los comunicados destino
            $data = DB::select('EXEC com.sp_ObtenerComunicadosDestinoPorPersona ?,?', $solicitud);
            return ResponseHandler::success($data);
       } catch (Exception $e) {
            return ResponseHandler::error("Error al obtener comunicados destino", 500, $e->getMessage());
       }
    }

    public function obtenerInstitucionesEspecialista(Request $request){
        $iPersId = $this->decodeValue($request->input('iPersId'));
        try {
            $data = DB::select('EXEC com.Sp_SEL_institucionesEspecialista ?', [$iPersId]);
            return ResponseHandler::success($data);
        } catch (Exception $e) {
            return ResponseHandler::error("Error al obtener instituciones", 500, $e->getMessage());
        }
    }
    
    public function obtenerDocentesPorInstitucion(Request $request) {
        $iIieeId = $request->input('iIieeId');
        try {
            $data = DB::select('EXEC com.Sp_SEL_docentesPorInstitucion ?', [$iIieeId]);
            return ResponseHandler::success($data);
        } catch (Exception $e) {
            return ResponseHandler::error("Error al obtener docentes", 500, $e->getMessage());
        }
    }
}
