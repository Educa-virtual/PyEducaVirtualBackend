<?php

namespace App\Models\enc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Docente extends Model
{
    public static function selDocentesFiltroEncuesta($params) {
        return DB::select("EXEC [enc].[Sp_SEL_docentesFiltroEncuesta] @iYAcadId=?, @iUgelId=?, @iNivelTipoId=?,
        @iIieeId=?, @iSedeId=?", $params);
    }
}
