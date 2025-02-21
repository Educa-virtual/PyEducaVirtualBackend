<?php

namespace App\Repositories\Grl;

use Illuminate\Support\Facades\DB;

class PersonasRepository
{
    public static function obtenerPersonaPorId($id)
    {
        $persona = DB::selectOne(
            'EXEC grl.Sp_SEL_personasXiPersId @_iPersId = ?',
            [$id]
        );
        return $persona;
    }
}
