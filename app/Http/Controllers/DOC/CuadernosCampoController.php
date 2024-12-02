<?php

namespace App\Http\Controllers\doc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;

class CuadernosCampoController extends Controller
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function obtenerCuadernosCampo(Request $request)
    {
        $request['iPersId'] = is_null($request->iPersId)
            ? null
            : (is_numeric($request->iPersId)
                ? $request->iPersId
                : ($this->hashids->decode($request->iPersId)[0] ?? null));

        $request['iYearId'] = is_null($request->iYearId)
            ? null
            : (is_numeric($request->iYearId)
                ? $request->iYearId
                : ($this->hashids->decode($request->iYearId)[0] ?? null));

        try {
            $data = DB::select("
                	SELECT
					 acur.iCursoId
					,acur.cCursoNombre
					,asil.iSilaboId
					,cc.cCuadernoDescripcion
					,cc.cCuadernoUrl
					,cc.iCuadernoId
					
					FROM acad.cursos AS acur
					INNER JOIN acad.cursos_niveles_grados   AS acunig   ON acunig.iCursoId=acur.iCursoId
					INNER JOIN acad.nivel_grados            AS angr     ON angr.iNivelGradoId=acunig.iNivelGradoId
					INNER JOIN acad.ies_cursos              AS aiecur   ON aiecur.iCursosNivelGradId=acunig.iCursosNivelGradId
					INNER JOIN acad.docente_cursos          AS adocu    ON adocu.iIeCursoId=aiecur.iIeCursoId
					INNER JOIN acad.docentes                AS adoc     ON adoc.iDocenteId=adocu.iDocenteId
					INNER JOIN acad.semestre_academicos     AS asema    ON asema.iSemAcadId=adocu.iSemAcadId AND asema.iYAcadId=adocu.iYAcadId
					INNER JOIN acad.year_academicos         AS ayeac    ON ayeac.iYAcadId=asema.iYAcadId
					LEFT JOIN acad.silabos					AS asil     ON asil.idDocCursoId=adocu.idDocCursoId AND asil.iSemAcadId=asema.iSemAcadId AND asil.iYAcadId=asema.iYAcadId
					LEFT JOIN doc.cuadernos_campo AS cc ON cc.iSilaboId = asil.iSilaboId 
					WHERE adoc.iPersId = '".$request->iPersId."' AND ayeac.iYearId='".$request->iYearId."' AND asil.iSilaboId is not null
					
            ");

            $response = ['validated' => true, 'mensaje' => 'Se octuvo la información exitosamente.', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function guardarFichas(Request $request)
    {
        $request['iSilaboId'] = is_null($request->iSilaboId)
            ? null
            : (is_numeric($request->iSilaboId)
                ? $request->iSilaboId
                : ($this->hashids->decode($request->iSilaboId)[0] ?? null));
        try {
            $data = DB::select("
                	SELECT
					 acur.iCursoId
					,acur.cCursoNombre
					,asil.iSilaboId
					,cc.cCuadernoDescripcion
					,cc.cCuadernoUrl
					,cc.iCuadernoId
					
					FROM acad.cursos AS acur
					INNER JOIN acad.cursos_niveles_grados   AS acunig   ON acunig.iCursoId=acur.iCursoId
					INNER JOIN acad.nivel_grados            AS angr     ON angr.iNivelGradoId=acunig.iNivelGradoId
					INNER JOIN acad.ies_cursos              AS aiecur   ON aiecur.iCursosNivelGradId=acunig.iCursosNivelGradId
					INNER JOIN acad.docente_cursos          AS adocu    ON adocu.iIeCursoId=aiecur.iIeCursoId
					INNER JOIN acad.docentes                AS adoc     ON adoc.iDocenteId=adocu.iDocenteId
					INNER JOIN acad.semestre_academicos     AS asema    ON asema.iSemAcadId=adocu.iSemAcadId AND asema.iYAcadId=adocu.iYAcadId
					INNER JOIN acad.year_academicos         AS ayeac    ON ayeac.iYAcadId=asema.iYAcadId
					LEFT JOIN acad.silabos					AS asil     ON asil.idDocCursoId=adocu.idDocCursoId AND asil.iSemAcadId=asema.iSemAcadId AND asil.iYAcadId=asema.iYAcadId
					LEFT JOIN doc.cuadernos_campo AS cc ON cc.iSilaboId = asil.iSilaboId 
					WHERE asil.iSilaboId = '".$request->iSilaboId."'
					
            ");
            if($data[0]->iCuadernoId > 0){
                $query = DB::update("
                UPDATE doc.cuadernos_campo 
                SET cCuadernoUrl = '".$request->cCuadernoUrl."'
                WHERE iCuadernoId = '".$data[0]->iCuadernoId."'
            ");
            }
            else{
                $query = DB::update("
                    INSERT INTO doc.cuadernos_campo (cCuadernoUrl,iSilaboId)
                    VALUES ('".$request->cCuadernoUrl."','".$request->iSilaboId."')
                ");
            }
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.', 'query' => $query];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }
}
