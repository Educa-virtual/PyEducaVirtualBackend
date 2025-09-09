<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class YearAcademico
{
    public static function selYearAcademico($iYAcadId) {
        $data = DB::selectOne("SELECT * FROM acad.year_academicos WHERE iYAcadId=?", [$iYAcadId]);
        return $data;
    }
}
