<?php

namespace App\Models\enc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Categoria extends Model
{
    public static function selCategorias() {
        return DB::select("EXEC [enc].[Sp_SEL_categorias]");
    }

    public static function insCategorias($params) {
        return DB::select("EXEC [enc].[Sp_INS_categorias] @cNombre=?, @cDescripcion=?", $params);
    }

    public static function selCategoriasTotalEncuestas() {
        return DB::select("EXEC [enc].[Sp_SEL_categoriasTotalEncuestas]");
    }
}
