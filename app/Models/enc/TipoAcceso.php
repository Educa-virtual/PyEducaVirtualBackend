<?php

namespace App\Models\enc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TipoAcceso extends Model
{
    public static function selTiposAcceso() {
        return DB::select("SELECT * FROM enc.tipos_acceso_resultado WHERE iEstadoId=1
        ORDER BY iTipoAccesoId");
    }
}
