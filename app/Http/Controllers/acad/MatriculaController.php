<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
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
        
        // $request contiene datos de matricula
        $estudiante = $request->estudiante;
        $representante = $request->representante;

        // 1. Paso: Validar y/o registrar representante legal
        if( $representante->iPers_id ) {
            $persona = DB::select('SELECT * FROM grl.persona WHERE iPersId = ?, iTipoIdentId = ?, cPersDocumento = ?');
            if( count($persona) != 1 ) {
                $response = ['validated' => false, 'message' => 'Los datos del representante son incorrectos', 'data' => []];
                $codeResponse = 500;
                return new JsonResponse($response, $codeResponse);
            }
        } else {
            $parametros = [
                $representante->iTipoIdentId,
                $representante->cPersDocumento,
                $representante->cPersNombre,
                $representante->cPersPaterno,
                $representante->cPersMaterno,
            ];
            DB::beginTransaction();
            try {
                $representante->iPersId = DB::select('INSERT INTO grl.personas(iTipoIdentId, cPersDocumento, cPersNombre, cPersPaterno, cPersMaterno VALUES (?,?,?,?,?) RETURNING iPersId', $parametros);
                $estudiante->merge(['iPersRepresentanteLegalId' => $representante->iPersId]);
            } catch (\Exception $e) {
                DB::rollBack();
                $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
                $codeResponse = 500;
                return new JsonResponse($response, $codeResponse);
            }
        }

        // 2. Paso: Validar y/o registrar estudiante
        if( !$estudiante->iPersId ) {
            // 2.1. Caso: Es nueva persona y nuevo estudiante
            // 2.1.1. Paso: Registrar como persona
            $parametros = [
                $estudiante->iTipoIdentId,
                $estudiante->cPersDocumento,
                $estudiante->cPersNombre,
                $estudiante->cPersPaterno,
                $estudiante->cPersMaterno,
                $estudiante->iPersRepresentanteLegalId,
            ];
            DB::beginTransaction();
            try {
                $estudiante->iPersId = DB::select('INSERT INTO grl.personas(iTipoIdentId, cPersDocumento, cPersNombre, cPersPaterno, cPersMaterno, iPersRepresentanteLegalId VALUES (?,?,?,?,?,?) RETURNING iPersId', $parametros);
                $estudiante->merge(['iPersId' => $estudiante->iPersId]);
            } catch (\Exception $e) {
                DB::rollBack();
                $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
                $codeResponse = 500;
                return new JsonResponse($response, $codeResponse);
            }
            // 2.1.2. Paso: Registrar como estudiante
            $parametros = [
                'iPersId' => $estudiante->iPersId,
                'dtEstIngreso' => now(),
                'cEstNombres' => $estudiante->cPersNombre,
                'cEstPaterno' => $estudiante->cPersPaterno,
                'cEstMaterno' => $estudiante->cPersMaterno,
            ];
            DB::beginTransaction();
            try {
                $iEstudianteId = DB::select('INSERT INTO acad.estudiantes(iPersId, dtEstIngreso, cEstNombres, cEstPaterno, cEstMaterno) VALUES (?,?,?,?,?) RETURNING iEstudianteId', $parametros);
                $request->merge(['iEstudianteId' => $iEstudianteId]);
            } catch (\Exception $e) {
                DB::rollBack();
                $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
                $codeResponse = 500;
                return new JsonResponse($response, $codeResponse);
            }
        } elseif( !$request->iEstudianteId ) {
            // 2.2. Caso: Existe persona pero nuevo estudiante
            // 2.2.1. Validar y/o registrar estudiante
            $parametros = [
                'iPersId' => $estudiante->iPersId,
                'dtEstIngreso' => now(),
                'cEstNombres' => $estudiante->cPersNombre,
                'cEstPaterno' => $estudiante->cPersPaterno,
                'cEstMaterno' => $estudiante->cPersMaterno,
            ];
            DB::beginTransaction();
            try {
                $iEstudianteId = DB::select('INSERT INTO acad.estudiantes(iPersId, dtEstIngreso, cEstNombres, cEstPaterno, cEstMaterno, iPersRepresentanteLegalId) VALUES (?,?,?,?,?,?) RETURNING iEstudianteId', $parametros);
                $request->merge(['iEstudianteId' => $iEstudianteId]);
            } catch (\Exception $e) {
                DB::rollBack();
                $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
                $codeResponse = 500;
                return new JsonResponse($response, $codeResponse);
            }
        }

        // 3. Paso: Registrar matricula

        // Establecer valores por defecto para proforma/solicitud de matricula
        $parametros = [
            $estudiante->iEstudianteId,
            $request->iSemAcadId,
            $request->iYAcadId,
            $request->iTipoMatrId,
            $request->bMatrReservado,
            $request->dtMatrReservado,
            $request->cMatrObservaciones,
            $request->iSedeId,
            $request->iNivelGradoId,
            $request->iSeccionId,
            $request->iTurnoId,
            $request->bMatrEsProforma ?? 1,
            $request->bMatrEsProforma ?? 1,
            $request->bMatrEsRegular ?? 1,
            $request->dtMatrFechaProforma ?? now(),
            $request->iEstado ?? 1,
            $request->iSessionId,
            $request->dtCreado ?? now(),
            $request->dtActualizado ?? now(),
        ];

        DB::beginTransaction();
        try {
            DB::select('INSERT INTO acad.matricula(iEstudianteId, iSemAcadId, iYAcadId, iTipoMatrId, bMatrReservado, dtMatrReservado, cMatrObservaciones, iSedeId, iNivelGradoId, iSeccionId, iTurnoId, bMatrEsProforma, bMatrEsRegular, dtMatrFechaProforma, iEstado, iSesionId, dtCreado, dtActualizado),
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => ''];
            $codeResponse = 200;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => ''];
            $codeResponse = 500;
        }
        DB::commit();
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
