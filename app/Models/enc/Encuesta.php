<?php

namespace App\Models\enc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Encuesta extends Model
{
    public static function selEncuestasXCategoria($iCategoriaEncuestaId) {
        return DB::select("EXEC [enc].[Sp_SEL_encuestasXCategoria] @iCategoriaEncuestaId=?", [$iCategoriaEncuestaId]);
    }
}
