<?php

namespace App\Models\hor;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Horario extends Model
{
    public static function selHorario($parametros)
    {
        file_put_contents('D:\hor.txt',json_encode($parametros));
        return DB::select("EXEC hor.SP_SEL_horarioEstudiante @iYAcadId=?, @iSedeId=?, @iNivelGradoId=?, @iSeccionId=?",$parametros);
    }
}
