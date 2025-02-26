<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use App\Services\ParseSqlErrorService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class MatriculaController extends Controller
{
    protected $hashids;
    protected $parseSqlErrorService;

    public function __construct()
    {
        $this->hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
        $this->parseSqlErrorService = new ParseSqlErrorService;
    }

    /**
     * Lista todos los grados
     * @param Request $request
     * @return JsonResponse
     */
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

    /**
     * Guarda una matricula
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request)
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

        try {
            $data = DB::select('EXEC acad.Sp_INS_matricula ?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch(\Exception $e) {
            $error_message = $this->parseSqlErrorService->parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }

    /**
     * Busca matriculas segun parametros
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
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
            $error_message = $this->parseSqlErrorService->parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        
        return new JsonResponse($response, $codeResponse);
    }

    /**
     * Busca una matricula segun parametros
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request)
    {
        $parametros = [
            $request->iMatrId,
            $request->iCredSesionId,
        ];

        try {
            $data = DB::select("EXEC acad.Sp_SEL_matriculaPorId ?,?", $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        }
        catch (\Exception $e) {
            $error_message = $this->parseSqlErrorService->parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        
        return new JsonResponse($response, $codeResponse);
    }

    /**
     * Elimina una matricula
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        $parametros = [
            $request->iMatrId,
            $request->iCredSesionId,
        ];

        try {
            $data = DB::select("EXEC acad.Sp_DEL_matriculaPorId ?,?", $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        }
        catch (\Exception $e) {
            $error_message = $this->parseSqlErrorService->parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        
        return new JsonResponse($response, $codeResponse);
    }
}
