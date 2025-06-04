<?php

namespace App\Models\grl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Prioridad extends Model
{
    public static function selPrioridades()
    {
        return DB::select("EXEC grl.SP_SEL_prioridades");
    }
}
