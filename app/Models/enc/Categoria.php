<?php

namespace App\Models\enc;

use App\Helpers\VerifyHash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Categoria extends Model
{

    public static function selCategorias($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC enc.Sp_SEL_categorias $placeholders", $parametros);
    }

    public static function selCategoria($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->iCateId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_SEL_categoria $placeholders", $parametros);
    }

    public static function insCategoria($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->cCateNombre,
            $request->cCateDescripcion,
            $request->cCateImagenNombre,
            $request->bCatePermisoDremo,
            $request->bCatePermisoUgel,
            $request->bCatePermisoDirector,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_INS_categoria $placeholders", $parametros);
    }

    public static function updCategoria($request)
    {
        $params = [
            $request->header('iCredEntPerfId'),
            $request->iCateId,
            $request->cCateNombre,
            $request->cCateDescripcion,
            $request->cCateImagenNombre,
            $request->bCatePermisoDremo,
            $request->bCatePermisoUgel,
            $request->bCatePermisoDirector,
        ];
        $placeholders = implode(',', array_fill(0, count($params), '?'));
        return DB::selectOne("EXEC enc.Sp_UPD_categoria $placeholders", $params);
    }

    public static function delCategoria($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iCateId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC enc.Sp_DEL_categoria $placeholders", $parametros);
    }
}
