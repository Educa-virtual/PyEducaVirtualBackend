<?php

namespace App\Http\Controllers;

use App\Enums\Perfil;
use App\Models\Competencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompetenciaController extends Controller
{
    public function getCompetenciasPorCurso(Request $request)
    {
        $validated = $request->validate([
            'iCursoId' => 'required|integer',
            'iNivelTipoId' => 'required|integer'
        ]);
        
        try {
            $competencias = DB::select(
                'EXEC acad.Sp_SEL_compatenciasXCursoIdXCurricula ?, ?',
                [
                    $validated['iCursoId'],
                    $validated['iNivelTipoId']
                ]
            );
            $resultado = array_map(function($competencia) {
                $competencia->capacidades = json_decode($competencia->capacidades) ?? [];
                return $competencia;
            }, $competencias);
            
            return response()->json([
                'success' => true,
                'data' => $resultado
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las competencias',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getCompetenciasPorCursoEloquent(Request $request)
    {
        $validated = $request->validate([
            'iCursoId' => 'required|integer',
            'iNivelTipoId' => 'required|integer'
        ]);
        
        try {
            $competencias = DB::table('acad.competencias_cursos as co')
                ->join('acad.cursos as c', 'c.iCursoId', '=', 'co.iCursoId')
                ->join('acad.curriculo_competencias as cu', 'cu.iCompetenciaId', '=', 'co.iCompetenciaId')
                ->select(
                    'co.iCompCursoId',
                    'c.iCursoId',
                    'c.iTipoCursoId',
                    'c.cCursoNombre',
                    'cu.iCompetenciaId',
                    'cu.cCompetenciaNro',
                    'cu.cCompetenciaNombre',
                    'cu.cCompetenciaDescripcion'
                )
                ->where('co.iNivelTipoId', $validated['iNivelTipoId'])
                ->where('co.iCursoId', $validated['iCursoId'])
                ->where('co.iEstado', 1)
                ->get();
            
            foreach ($competencias as $competencia) {
                $competencia->capacidades = DB::table('acad.curriculo_capacidades')
                    ->select('iCapacidadId', 'cCapacidadNombre', 'cCapacidadDescripcion')
                    ->where('iCompetenciaId', $competencia->iCompetenciaId)
                    ->get();
            }
            
            return response()->json([
                'success' => true,
                'data' => $competencias
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las competencias',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}