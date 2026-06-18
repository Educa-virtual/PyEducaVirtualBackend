<?php

namespace App\Models\acad;

use App\Helpers\VerifyHash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Estudiante extends Model
{
    public static function selEstudiantes(Object $request)
    {
        $parametros = [
            $request->iEstudianteId,
            $request->iPersId,
            $request->iCurrId,
            $request->cEstCodigo,
            $request->dtEstIngreso,
            $request->cEstNombres,
            $request->cEstPaterno,
            $request->cEstMaterno,
            $request->dtEstFechaNacimiento,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.Sp_SEL_estudiantes $placeholders", $parametros);
    }

    public static function selEstudiante(Object $request)
    {
        $parametros = [
            $request->iEstudianteId,
            $request->iPersId,
            $request->cEstCodigo,
            $request->iTipoIdentId,
            $request->cPersDocumento,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_SEL_estudiante $placeholders", $parametros);
    }

    public static function insEstudiante(Object $request)
    {
        $parametros = [
            $request->iPersId,
            $request->iCurrId ?? 1,
            $request->cPersNombre,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->dPersNacimiento,
            $request->cPersCertificado,
            $request->cPersDomicilio,
            $request->header('iCredEntPerfId'),
            $request->cEstCodigo,
            $request->cEstUbigeo,
            $request->cEstTelefono,
            $request->cEstCorreo,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_INS_estudiante $placeholders", $parametros);
    }

    public static function updEstudiante(Object $request)
    {
        $parametros = [
            $request->iEstudianteId,
            $request->iPersId,
            $request->iCurrId,
            $request->cPersNombre,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->dPersNacimiento,
            $request->cEstPartidaNacimiento,
            $request->cPersDomicilio,
            $request->header('iCredEntPerfId'),
            $request->cEstCodigo,
            $request->cEstUbideo,
            $request->cEstTelefono,
            $request->cEstCorreo,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_UPD_estudiante $placeholders", $parametros);
    }

    public static function selIdEstudiantePorIdPersona($iEstudianteId)
    {
        return DB::selectOne("SELECT iEstudianteId FROM acad.estudiantes WHERE iPersId=?", [$iEstudianteId]);
    }

    public static function selIdCredIdPersEstudiantePorIeDocumento($cPersDocumento, $iSedeId)
    {
        return DB::selectOne("SELECT TOP 1 iCredEntPerfId, per.iPersId
FROM seg.credenciales_entidades_perfiles AS cep
INNER JOIN seg.credenciales_entidades AS ce ON ce.iCredEntId=cep.iCredEntId
INNER JOIN seg.credenciales AS cred ON cred.iCredId=ce.iCredId
INNER JOIN acad.estudiantes AS est ON est.iPersId=cred.iPersId
INNER JOIN acad.matricula AS mat ON mat.iEstudianteId=est.iEstudianteId
INNER JOIN grl.personas AS per ON per.iPersId=est.iPersId
WHERE per.cPersDocumento=? AND mat.iSedeId=?", [$cPersDocumento, $iSedeId]);
    }

    public static function selEstudiantePorIeDocumentoAnio($cPersDocumento, $iSedeId, $iYAcadId)
    {
        return DB::selectOne("SELECT per.cPersDocumento, est.iEstudianteId, mat.iMatrId, cPersPaterno, cPersMaterno, cPersNombre
FROM acad.estudiantes AS est
INNER JOIN grl.personas AS per ON per.iPersId=est.iPersId
INNER JOIN acad.matricula AS mat ON mat.iEstudianteId=est.iEstudianteId
WHERE per.cPersDocumento=? AND mat.iSedeId=? AND mat.iYAcadId=?
AND mat.iEstado=1", [$cPersDocumento, $iSedeId, $iYAcadId]);
    }

    public static function selObtenerCursoEstudiante(Request $request){

        $parametros = [
            $request->iEstudianteId,
            $request->iYAcadId,
            $request->iSedeId,
        ];

        $data = DB::select("execute acad.Sp_SEL_cursosXEstudianteAnioSemestre ?,?,?", $parametros);

        foreach ($data as $value) {
            $value->iCursoId = VerifyHash::encodexId($value->iCursoId);
            $value->iSilaboId = VerifyHash::encodexId($value->iSilaboId);
        }

        return $data;
    }
}
