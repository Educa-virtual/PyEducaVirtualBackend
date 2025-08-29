<?php

namespace App\Models\acad;

use Illuminate\Support\Facades\DB;

class TipoActividad
{
    public static function selTipoActividad () {
        $data = DB::select("SELECT * FROM aula.actividad_tipos ORDER BY cActTipoNombre");
        return $data;
    }
}
