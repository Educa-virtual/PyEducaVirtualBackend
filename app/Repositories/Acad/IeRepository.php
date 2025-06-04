<?php

namespace App\Repositories\Acad;

use Illuminate\Support\Facades\DB;

class IeRepository
{
    public static function directorPerteneceIe($iPersId, $iieeId)
    {
        // Perfil 4 es Director de IE
        $data = DB::selectOne("SELECT pfl.iPerfilId, cep.iCredEntId, cre.iCredId, ies.iIieeId, sed.iSedeId, per.iPersId
            FROM seg.credenciales_entidades cen
            INNER JOIN acad.sedes sed on sed.iSedeId = cen.iSedeId
            INNER JOIN acad.institucion_educativas ies on ies.iIieeId = sed.iIieeId
            INNER JOIN seg.credenciales cre on cen.iCredId = cre.iCredId
            INNER JOIN grl.personas per on cre.iPersId = per.iPersId
            INNER JOIN seg.credenciales_entidades_perfiles cep on cen.iCredEntId = cep.iCredEntId
            INNER JOIN seg.perfiles pfl on cep.iPerfilId = pfl.iPerfilId
            WHERE pfl.iPerfilId = 4 AND per.iPersId = ? AND ies.iIieeId = ?", [$iPersId, $iieeId]);
        return $data !== null;
    }

    public static function eliminarHorasExamen($iieeParticipaEval) {
        DB::statement("DELETE FROM [ere].[iiee_cursos_examen] WHERE iIeeParticipaId=?", [$iieeParticipaEval]);
    }
}
