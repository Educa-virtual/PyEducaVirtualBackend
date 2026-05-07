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

    public static function delFechasImportantes($parametros) {   
        return DB::update("UPDATE acad.fechas_importantes SET iEstado = 0 WHERE iFechaImpId = ?", [$parametros]);
    }

    public static function selFechasImportantes($datos) {

        $parametros = [
            $datos->route('iSedeId'),
            $datos->route('iYAcadId'),
        ];

        return DB::select("EXEC acad.Sp_SEL_FechasEspecialesIE ?,?", $parametros);
    }

    public static function selDependenciaFechas($datos) {

        $iFechaImpId = $datos->route('iFechaImpId');
        return DB::select("SELECT * FROM acad.fechas_importantes WHERE iEstado = 1 AND iDepFechaImpId = ?", [$iFechaImpId]);
    }
}
