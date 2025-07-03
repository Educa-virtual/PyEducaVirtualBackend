<?php

namespace App\Models\seg;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class Auditoria extends Model
{
    public static function selAuditoriaAccesosAutorizados($fechaInicio, $fechaFin)
    {
        return DB::select("EXEC [seg].[Sp_SEL_auditoriaAccesosAutorizados] @fechaInicio=?, @fechaFin=?", [$fechaInicio, $fechaFin]);
    }

    public static function selAuditoriaAccesosFallidos($fechaInicio, $fechaFin)
    {
        return DB::select("EXEC [seg].[Sp_SEL_auditoriaAccesosFallidos] @fechaInicio=?, @fechaFin=?", [$fechaInicio, $fechaFin]);
    }

    public static function selAuditoriaConsultasDatabase($fechaInicio, $fechaFin)
    {
        return DB::select("EXEC [seg].[Sp_SEL_auditoriaConsultasDatabase] @fechaInicio=?, @fechaFin=?", [$fechaInicio, $fechaFin]);
    }

    public static function selAuditoriaConsultasBackend($fechaInicio, $fechaFin)
    {
        return DB::select("EXEC [seg].[Sp_SEL_auditoriaConsultasBackend] @fechaInicio=?, @fechaFin=?", [$fechaInicio, $fechaFin]);
    }
}
