<?php

namespace App\Models\acad;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FechaImportante extends Model
{
    public static function selFechasImportantesCalendario($params) {
        $data = DB::select("EXEC acad.SP_SEL_fechasImportantesCalendario @iSedeId=?, @iYAcadId=?", $params);
        return $data;
    }

    public static function selTiposFechasCalendario() {
        $data = DB::select("EXEC [acad].[SP_SEL_tiposFechasImportantesCalendario]");
        return $data;
    }
}
