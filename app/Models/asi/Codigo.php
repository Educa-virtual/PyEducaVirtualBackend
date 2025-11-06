<?php

namespace App\Models\asi;

use App\Helpers\VerifyHash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Codigo extends Model
{
    public static function obtenerCodigo(Request $request){
        $iPersId = VerifyHash::decodes($request->iPersId);
        $iSedeId = $request->iSedeId;
        $iYAcadId = $request->iYAcadId;
        $datos = [
            $iPersId,
            $iYAcadId,
            $iSedeId,
        ];
        $enviar = str_repeat('?,',count($datos)-1).'?';
        $procedimiento = "EXEC asi.Sp_SEL_codigo ".$enviar;
        $data = DB::SELECT($procedimiento, $datos);
        
        $grupo = (array) $data[0];
        $grupo["cMatrNumero"] = VerifyHash::encodexId($grupo["cMatrNumero"] ?? NULL);
        $grupo["cPersDocumento"] = VerifyHash::encodexId($grupo["cPersDocumento"] ?? NULL);

        return $grupo;
    }
}
