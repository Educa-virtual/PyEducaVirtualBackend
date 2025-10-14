<?php

namespace App\Models\asi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AsistenciaControl extends Model
{
    public static function selAsistenciaEstudiantePorFecha($matricula, $fecha)
    {
        return DB::select(
            "EXEC  [asi].[SP_SEL_asistenciaControlEstudiantePorFecha] @iEstudianteId=?, @iYAcadId=?, @iSedeId=?, @dtCtrlAsistencia=?",
            [$matricula->iEstudianteId, $matricula->iYAcadId, $matricula->iSedeId, $fecha]
        );
    }
}
