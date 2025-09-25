<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class InstitucionEducativa
{
    public static function selInstitucionEducativa($iIieeId) {
        return DB::selectOne("SELECT * FROM acad.institucion_educativas WHERE iIieeId=?", [$iIieeId]);
    }
}
