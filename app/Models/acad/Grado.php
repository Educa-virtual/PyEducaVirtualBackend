<?php

namespace App\Models\acad;

use App\Helpers\VerifyHash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Grado extends Model
{
    public static function selGradoDocente(Request $request) {
        
        $iDocenteId = VerifyHash::decodes($request->iDocenteId);
        return DB::select(
                "SELECT
                DISTINCT
                ag.iGradoId
                ,ag.cGradoNombre+' ('+ant.cNivelTipoNombre+')' AS cGrado 
                ,ag.cGradoAbreviacion
                ,ag.cGradoRomanos
                ,ang.iNivelGradoId
                ,ant.cNivelTipoNombre
                FROM acad.grados AS ag
                INNER JOIN acad.nivel_grados AS ang
                ON ang.iGradoId = ag.iGradoId
                INNER JOIN acad.nivel_ciclos AS anc
                ON anc.iNivelCicloId = ang.iNivelCicloId
                INNER JOIN acad.nivel_tipos AS ant
                ON ant.iNivelTipoId = anc.iNivelTipoId
                INNER JOIN acad.cursos_niveles_grados AS acng
                ON acng.iNivelGradoId = ang.iNivelGradoId
                INNER JOIN acad.ies_cursos AS aic
                ON aic.iCursosNivelGradId = acng.iCursosNivelGradId
                INNER JOIN acad.docente_cursos AS adc
                ON adc.iIeCursoId = aic.iIeCursoId
                AND adc.iDocenteId = ?",[$iDocenteId]
        );

    }
}
