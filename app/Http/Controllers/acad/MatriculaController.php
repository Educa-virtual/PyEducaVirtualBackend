<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class MatriculaController extends Controller
{
    protected $hashids;
    protected $iMatrId;
    protected $iEstudianteId;
    protected $iSemAcadId;
    protected $iYAcadId;
    protected $iTipoMatrId;
    protected $iCurrId;


    public function __construct()
    {
        $this->hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
    }

    public function list(Request $request)
    {
        $request->validate(
            [
                'opcion' => 'required',
            ],
            [
                'opcion.required' => 'Hubo un problema al obtener la acción',
            ]
        );
        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iMatrId           ?? NULL,
            $iEstudianteId ?? NULL,
            $iSemAcadId    ?? NULL,
            $iYAcadId  ?? NULL,
            $iTipoMatrId   ?? NULL,
            $iCurrId   ?? NULL,
            $request->dtMatrMigracion   ?? NULL,
            $request->dtMatrFecha   ?? NULL,
            $request->cMatrNumero   ?? NULL,
            $request->bMatrEsProforma   ?? NULL,
            $request->bMatrEsRegular    ?? NULL,
            $request->dtMatrFechaProforma   ?? NULL,
            $request->bMatrReservado    ?? NULL,
            $request->dtMatrReservado   ?? NULL,
            $request->bMatrReanudado    ?? NULL,
            $request->dtMatrReanudado   ?? NULL,
            $request->nMatrCosto    ?? NULL,
            $request->cMatrNroRecibo    ?? NULL,
            $request->bMatrPagado   ?? NULL,
            $request->nMatrTotalCreditos    ?? NULL,
            $request->cMatrObservaciones    ?? NULL,
            $request->iMatrEstado   ?? NULL,
            $request->iEstado   ?? NULL,
            $request->iSesionId ?? NULL,
            $request->dtCreado  ?? NULL,
            $request->dtActualizado ?? NULL,
            $request->iSedeId   ?? NULL,
            $request->iTurnoId ?? NULL,
            $request->iSeccionId ?? NULL,
            $request->iNivelGradoId ?? NULL,

        ];

        try {
            $data = DB::select('exec acad.Sp_SEL_matricula
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function searchGradoSeccionTurnoConf(Request $request)
    {
        $parametros = [
            $request->opcion,
            $request->iSedeId,
            $request->iYAcadId,
            $request->iNivelGradoId,
            $request->iSeccionId,
            $request->iTurnoId,
            $request->iCredSesionId,
        ];
        try {
            $data = DB::select("EXEC acad.Sp_SEL_grado_seccion_turno_conf ?,?,?,?,?,?,?", $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        }
        catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }        
        return new JsonResponse($response, $codeResponse);
    }

    public function searchGradoSeccion(Request $request)
    {
        $parametros = [
            $request->iSedeId,
            $request->iYAcadId,
        ];

        // TODO: Poner en procedimiento almacenado
        try {
            $matriculas = DB::select(' WITH secciones AS (
                    SELECT cIieeNombre, sd.iSedeId, cSedeNombre, ngd.iNivelGradoId, cAmbienteNombre, mds.cModalServNombre, cGradoAbreviacion, scc.iSeccionId, cSeccionNombre, iDetConfCantEstudiantes, tn.iTurnoId, tn.cTurnoNombre, nti.cNivelTipoNombre
                    FROM [acad].[detalle_config_grados_secciones] dcgs
                        JOIN acad.iiee_ambientes ia ON ia.iIieeAmbienteId = dcgs.iIieeAmbienteId
                        JOIN acad.sedes sd ON sd.iSedeId = ia.iSedeId
                        JOIN acad.institucion_educativas ie ON sd.iIieeId = ie.iIieeId
                        JOIN acad.nivel_tipos nti ON ie.iNivelTipoId = nti.iNivelTipoId
                        JOIN acad.turnos tn ON dcgs.iTurnoId = tn.iTurnoId
                        JOIN acad.modalidad_servicios mds ON mds.iModalServId = dcgs.iModalServId
                        JOIN acad.secciones scc ON dcgs.iSeccionId = scc.iSeccionId
                        JOIN acad.nivel_grados ngd ON ngd.iNivelGradoId = dcgs.iNivelGradoId
                        JOIN acad.grados gd ON ngd.iGradoId = gd.iGradoId
                ) SELECT secciones.*, coalesce(mtr.inscritos,0) AS inscritos
                FROM secciones
                    LEFT JOIN (
                        SELECT iSedeId, iNivelGradoId, iSeccionId, iTurnoId, count(*) as inscritos
                        FROM acad.matricula
                        WHERE iMatrEstado = 1 AND bMatrEsProforma = 0
                        GROUP BY iSedeId, iNivelGradoId, iSeccionId, iTurnoId
                    ) mtr ON mtr.iSedeId = secciones.iSedeId AND mtr.iNivelGradoId = secciones.iNivelGradoId AND mtr.iSeccionId = secciones.iSeccionId AND mtr.iTurnoId = secciones.iTurnoId', $parametros);
            $response = ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $matriculas];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function searchNivelGrado(Request $request)
    {
        try {
            $data = DB::select("SELECT ng.iNivelGradoId, n.cNivelNombre, nt.cNivelTipoNombre, c.cCicloNombre, g.cGradoAbreviacion, g.cGradoNombre
                FROM acad.nivel_grados ng
                    JOIN acad.grados g ON ng.iGradoId = g.iGradoId
                    JOIN acad.nivel_ciclos nc ON nc.iNivelCicloId = ng.iNivelCicloId
                    JOIN acad.ciclos c ON nc.iCicloId = c.iCicloId
                    JOIN acad.nivel_tipos nt ON nc.iNivelTipoId = nt.iNivelTipoId
                    JOIN acad.niveles n ON nt.iNivelId = n.iNivelId");
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }

    public function guardar(Request $request)
    {
        $parametros = [
            $request->iEstudianteId,
            $request->iYAcadId,
            $request->iTipoMatrId,
            $request->iSedeId,
            $request->iNivelGradoId,
            $request->iTurnoId,
            $request->iSeccionId,
            $request->dtMatrFecha,
            $request->cMatrObservacion,
            $request->iCredSesionId
        ];
        $data = DB::select('EXEC acad.Sp_INS_matricula ?,?,?,?,?,?,?,?,?,?', $parametros);
        if( $data[0]->iResult ) {
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'message' => $data[0]->cMensaje, 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }

    public function search(Request $request)
    {
        $parametros = [
            $request->iSedeId,
            $request->iSemAcadId,
            $request->iYAcadId,
            $request->iNivelGradoId,
            $request->iSeccionId,
            $request->iTurnoId,
            null,
            null,
            null,
            null,
            null,
            $request->iCredSesionId,
        ];

        try {
            $data = DB::select("EXEC acad.Sp_SEL_matriculas ?,?,?,?,?,?,?,?,?,?,?,? ", $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        }
        catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }
        
        return new JsonResponse($response, $codeResponse);
    }
}
