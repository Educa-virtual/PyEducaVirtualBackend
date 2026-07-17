<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class YearAcademico
{
    public static function selYearAcademico($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_SEL_yearAcademico $placeholders", $parametros);
    }

    public static function selYearAcademicoPorId($iYAcadId) {
        $data = DB::selectOne("SELECT * FROM acad.year_academicos WHERE iYAcadId=?", [$iYAcadId]);
        return $data;
    }

    public static function selYearAcademicoPorAnio($anio) {
        $data = DB::selectOne("SELECT * FROM acad.year_academicos WHERE iYearId=?", [$anio]);
        return $data;
    }
}
