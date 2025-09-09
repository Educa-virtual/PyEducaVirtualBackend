<?php

namespace App\Models\enc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TiempoDuracion extends Model
{
    public static function selTiemposDuracion() {
        return DB::select("SELECT * FROM enc.tiempo_duracion WHERE iEstado=1
        ORDER BY iTiemDurId");
    }
}
