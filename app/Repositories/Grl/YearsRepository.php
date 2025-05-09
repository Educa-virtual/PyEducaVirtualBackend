<?php

namespace App\Repositories\grl;

use Illuminate\Support\Facades\DB;

class YearsRepository
{
    public static function obtenerYearPorId($id)
    {
        $year = DB::selectOne(
            'EXEC grl.Sp_SEL_yearsXiYearId @_iYearId = ?',
            [$id]
        );
        return $year;
    }
}
