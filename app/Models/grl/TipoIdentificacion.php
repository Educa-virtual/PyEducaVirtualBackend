<?php

namespace App\Models\grl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TipoIdentificacion extends Model
{
    public static function selTipoIdentificacion(){
        return DB::select('SELECT iTipoIdentId, cTipoIdentNombre, cTipoIdentSigla, iTipoIdentLongitud, cTipoIdentCodigoSunat1, cTipoIdentCodigoSunat2, iTipoIdentCodigoAFPnet FROM grl.tipos_Identificaciones');
    }
}
