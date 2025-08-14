<?php

namespace App\Models\enc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Estudiante extends Model
{
    public static function selEstudiantesFiltroEncuesta($params) {
        return DB::select("EXEC [enc].[Sp_SEL_estudiantesFiltroEncuesta] @iYAcadId=?, @iUgelId=?, @iNivelTipoId=?,
        @iIieeId=?, @iSedeId=?", $params);
    }
}
