<?php

namespace App\Models\grl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Persona extends Model
{
    public static function selPersonaPorDocumento($documento)
    {
        return DB::selectOne("SELECT TOP 1 * FROM grl.personas WHERE cPersDocumento=?", [$documento]);
    }
}
