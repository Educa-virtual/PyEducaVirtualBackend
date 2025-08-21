<?php

namespace App\Models\enc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Director extends Model
{
    public static function selDirectoresFiltroEncuesta($params)
    {
        return DB::select("EXEC [enc].[Sp_SEL_directoresFiltroEncuesta] @iUgelId=?, @iNivelTipoId=?,
        @iIieeId=?, @iSedeId=?", $params);
    }
}
