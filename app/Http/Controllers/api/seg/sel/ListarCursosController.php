<?php

namespace App\Http\Controllers\api\seg\sel;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListarCursosController extends Controller
{
    public function cursos(Request $request){
        $persona_id = $request->iPersId;
        
        $sel_query = DB::select('select
                            acur.iCursoId,
                            acur.iCurrId,
                            acur.iTipoCursoId,
                            acur.iGradoId,
                            acur.iNivelId,
                            acur.cCursoNombre,
                            acur.nCursoCredTeoria,
                            acur.nCursoCredPractica,
                            acur.cCursoDescripcion,
                            acur.nCursoTotalCreditos,
                            acur.cCursoPerfilDocente,
                            acur.iCursoTotalHoras,
                            acur.iCursoEstado,
                            acur.iEstado
                            from acad.docente_cursos as adoc
                            inner join acad.docentes as acdoc on acdoc.iDocenteId=adoc.iDocenteId
                            inner join acad.ies_cursos as aies on aies.iIeCursoId=adoc.iIeCursoId
                            inner join acad.cursos as acur on acur.iCursoId=aies.iCursoId
                            where acdoc.iPersId=?',[$persona_id]);

        try{
            $response = [
                'validated' => true, 
                'message' => 'se obtuvo la información',
                'data' => $sel_query,
            ];

            $estado = 200;

        }catch(Exception $e){
            $response = [
                'validated' => true, 
                'message' => $e->getMessage(),
                'data' => [],
            ];
            $estado = 500;
        }

        return new JsonResponse($response,$estado);
    }
}
