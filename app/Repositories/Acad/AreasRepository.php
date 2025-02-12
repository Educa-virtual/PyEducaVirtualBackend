<?php

namespace App\Repositories\Acad;

use Illuminate\Support\Facades\DB;

class AreasRepository
{
    public static function obtenerAreaPorId($areaId)
    {
        $area = DB::selectOne('SELECT cCursoNombre, cGradoNombre, cGradoAbreviacion, cNivelTipoNombre
FROM acad.cursos AS acur
INNER JOIN acad.cursos_niveles_grados   AS acunig   ON acunig.iCursoId=acur.iCursoId
INNER JOIN acad.nivel_grados            AS angr     ON angr.iNivelGradoId=acunig.iNivelGradoId
INNER JOIN acad.grados                  AS agr      ON agr.iGradoId=angr.iGradoId
INNER JOIN acad.nivel_ciclos			AS anici	ON anici.iNivelCicloId=angr.iNivelCicloId
INNER JOIN acad.nivel_tipos				AS aniti	ON aniti.iNivelTipoId=anici.iNivelTipoId
INNER JOIN acad.niveles					AS ani		ON ani.iNivelId = aniti.iNivelId
WHERE iCursosNivelGradId=?', [$areaId]);
        return $area;
    }
}
