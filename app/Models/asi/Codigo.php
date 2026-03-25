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

        $data = DB::selectOne("SELECT gp.cPersDocumento,am.cMatrNumero FROM grl.personas AS gp
            INNER JOIN acad.estudiantes AS ae ON ae.iPersId = gp.iPersId
            INNER JOIN acad.matricula AS am
            ON am.iEstudianteId = ae.iEstudianteId AND am.iEstado = 1 AND am.iMatrEstado = 1 AND am.iSedeId = ? AND am.iYAcadId = ?
            WHERE gp.iPersId = ?",[$iSedeId,$iYAcadId,$iPersId]);

        $grupo = (array) $data;
        $grupo["cMatrNumero"] = VerifyHash::encodexId($grupo["cMatrNumero"] ?? NULL);
        $grupo["cPersDocumento"] = VerifyHash::encodexId($grupo["cPersDocumento"] ?? NULL);

        return $grupo;
    }
}
