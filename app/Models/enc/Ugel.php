<?php

namespace App\Models\enc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ugel extends Model
{
    public static function selUgelesFiltroEncuesta($params) {
        return DB::select("EXEC [enc].[Sp_SEL_ugelesFiltroEncuesta] @iUgelId=?", $params);
    }
}
