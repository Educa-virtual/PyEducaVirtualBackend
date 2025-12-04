<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class InstitucionEducativa
{
    public static function selInstitucionEducativa($iIieeId) {
        return DB::selectOne("SELECT * FROM acad.institucion_educativas WHERE iIieeId=?", [$iIieeId]);
    }

    public static function selInstitucionEducativaPorCodigoModular($codigoModular) {
        return DB::selectOne("SELECT * FROM acad.institucion_educativas WHERE cIieeCodigoModular=?", [$codigoModular]);
    }

    public static function selInstitucionEducativaPorSede($iSedeId) {
        return DB::selectOne("SELECT ie.* FROM acad.institucion_educativas AS ie
        INNER JOIN acad.sedes AS sede ON sede.iIieeId=ie.iIieeId
        WHERE sede.iSedeId=?", [$iSedeId]);
    }
    public static function selInstitucionEducativaNivel($iIieeId) {
        return DB::selectOne("SELECT ie.*, nt.cNivelTipoNombre, au.cUgelNombre
        FROM acad.institucion_educativas AS ie
        INNER JOIN acad.sedes AS sede ON sede.iIieeId = ie.iIieeId
        INNER JOIN acad.nivel_tipos AS nt ON nt.iNivelTipoId = ie.iNivelTipoId
        INNER JOIN acad.ugeles AS au ON au.iUgelId = ie.iUgelId
        WHERE ie.iIieeId=?", [$iIieeId]);
    }
}
