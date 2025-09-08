<?php

namespace App\Models\acad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Area extends Model
{
    public static function selAreasPorEvaluacionEstudiante($iEvaluacionId, $iEstudianteId) {
        return DB::select("EXEC [ere].[Sp_SEL_AreasPorEvaluacionEstudiante] @iEstudianteId=?, @iEvaluacionId=?",[$iEstudianteId, $iEvaluacionId]);
    }
}
