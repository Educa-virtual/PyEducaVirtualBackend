<?php

namespace App\Models\enc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Categoria extends Model
{

    public static function insCategoria($params)
    {
        return DB::select("EXEC [enc].[Sp_INS_categoria] @cNombre=?, @cDescripcion=?, @bPuedeCrearEspDremo=?,
        @bPuedeCrearAccesoEspUgel=?, @bPuedeCrearDirector=?, @cImagenUrl=?", $params);
    }

    public static function updCategoria($params)
    {
        return DB::select("EXEC [enc].[Sp_UPD_categoria] @iCategoriaEncuestaId=?, @cNombre=?, @cDescripcion=?,
        @bPuedeCrearEspDremo=?, @bPuedeCrearAccesoEspUgel=?, @bPuedeCrearDirector=?, @cImagenUrl=?", $params);
    }

    public static function delCategoria($params)
    {
        return DB::select("EXEC [enc].[Sp_DEL_categoria] @iCategoriaEncuestaId=?", $params);
    }

    public static function selCategorias()
    {
        return DB::select("EXEC [enc].[Sp_SEL_categorias]");
    }
}
