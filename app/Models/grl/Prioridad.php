<?php

namespace App\Models\grl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Prioridad extends Model
{
    public static function selPrioridades()
    {
        return DB::select("SELECT * FROM grl.prioridades ORDER BY iPrioridadId ASC");
    }
}
