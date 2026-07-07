<?php

namespace App\Models\acad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Curso extends Model
{
    public static function selCursos(object $request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iCursoId,
            $request->iCurrId,
            $request->iTipoCursoId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));

        return DB::select("EXEC acad.Sp_SEL_cursos $placeholders", $parametros);
    }
}
