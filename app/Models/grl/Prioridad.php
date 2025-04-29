<?php

namespace App\Models\grl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Prioridad extends Model
{
    public static function obtenerListaPrioridades()
    {
        //REVISAR QUERY
        return DB::select("SELECT iPrioridadId, cPrioridadNombre FROM grl.prioridades ORDER BY iPrioridadId");
    }
}
