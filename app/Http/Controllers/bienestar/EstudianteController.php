<?php
namespace App\Http\Controllers\bienestar;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class EstudianteController extends Controller
{
    public function obtenerEstudiantesPorAnio($idApod, $iIieeId, $anio): JsonResponse
    {
        $estudiantes = DB::select("
            SELECT 
                e.iEstudianteId,
                e.iPersId,
                e.cEstPaterno,
                e.cEstMaterno,
                e.cEstNombres,
                g.cGradoNombre,
                s.cSeccionNombre,
                p.cPersDocumento,
                se.iSedeId,
                ie.iIieeId,
                ie.cIieeNombre,
                fdg.dtFichaDG
            FROM acad.estudiantes e
            INNER JOIN grl.personas p ON e.iPersId = p.iPersId
            INNER JOIN acad.matricula m ON e.iEstudianteId = m.iEstudianteId
            INNER JOIN acad.sedes se ON m.iSedeId = se.iSedeId
            INNER JOIN acad.institucion_educativas ie ON se.iIieeId = ie.iIieeId
            INNER JOIN acad.grados g ON m.iNivelGradoId = g.iGradoId
            INNER JOIN acad.secciones s ON m.iSeccionId = s.iSeccionId
            INNER JOIN obe.ficha_datos_grales fdg ON p.iPersId = fdg.iPersId
            WHERE e.iPersApoderadoId = ? 
              AND ie.iIieeId = ? 
              AND YEAR(fdg.dtFichaDG) = ?
        ", [$idApod, $iIieeId, $anio]);
        return response()->json($estudiantes);
    }
}
