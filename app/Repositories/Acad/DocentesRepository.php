<?php

namespace App\Repositories\Acad;

use Illuminate\Support\Facades\DB;

class DocentesRepository
{
    public static function obtenerDocentePorId($docenteId)
    {
        $area = DB::selectOne('SELECT * FROM acad.docentes WHERE iDocenteId=?', [$docenteId]);
        return $area;
    }
}
