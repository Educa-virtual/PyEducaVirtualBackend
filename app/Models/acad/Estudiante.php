<?php

namespace App\Models\acad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Estudiante extends Model
{
    public static function selIdEstudiantePorIdPersona($iEstudianteId)
    {
        return DB::selectOne("SELECT iEstudianteId FROM acad.estudiantes WHERE iPersId=?", [$iEstudianteId]);
    }
}
