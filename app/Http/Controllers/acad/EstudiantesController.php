<?php

namespace App\Http\Controllers\acad;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Services\FormatearExcelMatriculasService;
use App\Services\LeerExcelService;
use App\Services\FormatearExcelPadresService;
use App\Services\ParseSqlErrorService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Exp;

class EstudiantesController extends Controller
{
    protected $hashids;
    protected $iEstudianteId;
    protected $leerExcelService;
    protected $formatearExcelPadresService;
    protected $parseSqlErrorService;
    protected $formatearExcelMatriculasService;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $this->leerExcelService = new LeerExcelService();
        $this->formatearExcelPadresService = new FormatearExcelPadresService();
        $this->parseSqlErrorService = new ParseSqlErrorService();
        $this->formatearExcelMatriculasService = new FormatearExcelMatriculasService();
    }

    public function obtenerCursosXEstudianteAnioSemestre(Request $request)
    {
        $request->validate(
            [
                'iEstudianteId' => 'required',
                'iYearId' => 'required',
            ],
            [
                'iEstudianteId.required' => 'Hubo un problema al obtener el iEstudianteId',
                'iYearId.required' => 'Hubo un problema al obtener el iYearId',
            ]
        );

        $parametros = [
            $request->iEstudianteId,
            $request->iYearId
        ];

        try {
            $data = DB::select("execute acad.Sp_SEL_cursosXEstudianteAnioSemestre ?,?", $parametros);

            foreach ($data as $key => $value) {
                $value->iCursoId = $this->hashids->encode($value->iCursoId);
                $value->iSilaboId = $this->hashids->encode($value->iSilaboId);
            }

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    /**
     * Guarda un estudiante
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request)
    {
        // primero guardar como persona
        $request->merge([
            'iTipoPersId' => 1, // Siempre persona natural
        ]);

        $parametros = [
            $request->iTipoPersId,
            $request->iTipoIdentId,
            $request->cPersDocumento,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->cPersNombre,
            $request->cPersSexo,
            $request->dPersNacimiento,
            $request->iTipoEstCivId,
            $request->cPersFotografia,
            $request->cPersRazonSocialNombre,
            $request->cPersRazonSocialCorto,
            $request->cPersRazonSocialSigla,
            $request->cPersDomicilio,
            $request->iCredId,
            $request->iNacionId,
            $request->iPaisId,
            $request->iDptoId,
            $request->iPrvnId,
            $request->iDsttId,
        ];

        try {
            $data = DB::select('EXEC grl.Sp_INS_personas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
            return new JsonResponse($response, $codeResponse);
        }

        // luego guardar como estudiante
        $parametros = [
            $data[0]->iPersId,
            1, // iCurrId
            $request->cPersNombre,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->dPersNacimiento,
            $request->cPersCertificado,
            $request->cPersDomicilio,
            $request->iCredId,
            $request->cEstCodigo,
            $request->cEstUbigeo,
            $request->cEstTelefono,
            $request->cEstCorreo,
            $request->iPersApoderadoId,
        ];

        try {
            $data = DB::select('EXEC acad.Sp_INS_estudiantes ?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            
            $data = DB::select('EXEC acad.Sp_SEL_estudiante_persona ?', [$data[0]->iEstudianteId]);

            $response = ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    /**
     * Actualiza un estudiante
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        // Primero actualizar datos de estudiante en tabla persona
        $parametros = [
            $request->iPersId,
            $request->cPersDocumento,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->cPersNombre,
            $request->cPersSexo,
            $request->dPersNacimiento,
            $request->iTipoEstCivId,
            $request->cPersFotografia,
            $request->cPersRazonSocialNombre,
            $request->cPersRazonSocialCorto,
            $request->cPersRazonSocialSigla,
            $request->cPersDomicilio,
            $request->iCredId,
            $request->iPersRepresentanteLegalId,
            $request->iNacionId,
            $request->iPaisId,
            $request->iDptoId,
            $request->iPrvnId,
            $request->iDsttId,
        ];

        try {
            $data = DB::select('EXEC grl.Sp_UPD_personas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
            return new JsonResponse($response, $codeResponse);
        }

        // luego actualizar datos en tabla estudiante
        $parametros = [
            $request->iEstudianteId,
            $data[0]->iPersId,
            $request->iCurrId,
            $request->cPersNombre,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->dPersNacimiento,
            $request->cEstPartidaNacimiento,
            $request->cPersDomicilio,
            $request->iCredId,
            $request->cEstCodigo,
            $request->cEstUbideo,
            $request->cEstTelefono,
            $request->cEstCorreo,
            $request->iPersApoderadoId,
        ];

        try {
            $data = DB::select('EXEC acad.Sp_UPD_estudiante ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $data = DB::select('EXEC acad.Sp_SEL_estudiantes_personas ?', [$data[0]->iEstudianteId]);
            $response = ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    /**
     * Buscar estudiantes segun parametros
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request){
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

        try {
            $data = DB::select('EXEC acad.Sp_SEL_estudiantes_personas ?,?,?,?,?,?,?,?,?', $parametros);

            $response = ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    /**
     * Buscar un estudiante segun parametros
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request){
        $parametros = [
            $request->iEstudianteId,
            $request->iPersId,
            $request->cEstCodigo
        ];
        try {
            $data = DB::select('EXEC acad.Sp_SEL_estudiante_persona ?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function importarEstudiantesPadresExcel(Request $request)
    {
        $datos_hojas = LeerExcelService::leer($request);
        
        $datos_hoja = FormatearExcelPadresService::formatear($datos_hojas);

        $parametros = [
            $request->iSedeId,
            $request->iSemAcadId,
            $request->iYAcadId,
            $request->iCredId,
            $datos_hoja['nivel'],
            $datos_hoja['modalidad'],
            $datos_hoja['turno'],
            json_encode($datos_hoja['estudiantes']),
            $datos_hoja['codigo_modular'],
        ];

        try {
            $data = DB::select('EXEC acad.Sp_INS_estudiantes_padres_masivo ?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        
        return new JsonResponse($response, $codeResponse);
    }

    public function importarEstudiantesMatriculasExcel(Request $request)
    {
        $datos_hojas = LeerExcelService::leer($request);

        $datos_hoja = FormatearExcelMatriculasService::formatear($datos_hojas);
        $parametros = [
            $request->iSedeId,
            $request->iSemAcadId,
            $request->iYAcadId,
            $request->iCredId,
            $datos_hoja['nivel'],
            $datos_hoja['modalidad'],
            $datos_hoja['turno'],
            json_encode($datos_hoja['estudiantes']),
            $datos_hoja['codigo_modular'],
        ];

        if( count($datos_hoja['estudiantes']) === 0 ) {
            return new JsonResponse(['message' => 'No se encontraron estudiantes', 'data' => []], 500);
        }

        try {
            $data = DB::select('EXEC acad.Sp_INS_estudiantesMatriculasMasivo ?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }
}
