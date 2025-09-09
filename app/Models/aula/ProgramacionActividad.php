<?php

namespace App\Models\aula;

use Illuminate\Support\Facades\DB;

class ProgramacionActividad
{
    public static function selCalendarioAcademicoEstudiante($iMatrId)
    {
        $data = DB::select("EXEC [aula].[SP_SEL_calendarioEstudiante] @iMatrId=?", [$iMatrId]);
        return $data;
    }
}
