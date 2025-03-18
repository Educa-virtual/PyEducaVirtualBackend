<?php
namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class EstudianteController extends Controller
{
        // Nuevo método que obtiene estudiantes por iIieeId y año
    public function obtenerEstudiantesPorAnio($iIieeId, $anio)
    {
        $estudiantes = DB::select("
            SELECT DISTINCT
                fdg.iPersId,
                e.cEstPaterno,
                e.cEstMaterno,
                e.cEstNombres,
                m.iEstudianteId,
                g.cGradoAbreviacion,
                s.cSeccionNombre,
                gp.cPersDocumento,
                fdg.dtFichaDG,
                sd.cSedeNombre,
                ie.iIieeId,
                ie.cIieeCodigoModular,
                ie.cIieeNombre
            FROM obe.ficha_datos_grales AS fdg
            INNER JOIN acad.estudiantes e ON fdg.iPersId = e.iPersId 
            INNER JOIN acad.matricula m ON e.iEstudianteId = m.iEstudianteId
            INNER JOIN acad.nivel_grados ng ON m.iNivelGradoId = ng.iNivelGradoId
            INNER JOIN acad.grados g ON ng.iGradoId = g.iGradoId
            INNER JOIN acad.secciones s ON m.iSeccionId = s.iSeccionId
            INNER JOIN grl.personas gp ON fdg.iPersId = gp.iPersId 
            INNER JOIN acad.sedes sd ON m.iSedeId = sd.iSedeId
            INNER JOIN acad.institucion_educativas ie ON sd.iIieeId = ie.iIieeId
            WHERE sd.iIieeId = ? AND YEAR(fdg.dtFichaDG) = ?
        ", [$iIieeId, $anio]);

        return response()->json($estudiantes);
    }
}







