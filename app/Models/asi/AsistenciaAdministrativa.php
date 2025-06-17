<?php

namespace App\Models\asi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsistenciaAdministrativa extends Model
{
    protected $schema = 'asi';
    protected $table = 'configuracion_horario';

    public static function buscarHorarioInstitucion(Request $request){
        
        $data = DB::select("EXEC [asi].[SP_SEL_configuracion_horario] ?");
        return $data;
    }
}
