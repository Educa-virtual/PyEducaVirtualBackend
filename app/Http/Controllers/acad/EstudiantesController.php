<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use App\Services\LeerExcelService;
use App\Services\FormatearExcelPadresService;
use App\Services\ParseSqlErrorService;
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

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $this->leerExcelService = new LeerExcelService();
        $this->formatearExcelPadresService = new FormatearExcelPadresService();
        $this->parseSqlErrorService = new ParseSqlErrorService();
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

    public function guardarEstudiantePersona(Request $request)
    {
        // primero guardar como persona
        $request->merge([
            'iTipoPersId' => 1, // Siempre persona natural
        ]);

        $this->validateGuardarPersona($request);
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

        DB::beginTransaction();

        try {
            $data = DB::select('EXEC grl.Sp_INS_personas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
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
            $request->cEstUbideo,
            $request->cEstPartidaNacimiento,
            $request->cEstTelefono,
            $request->cEstCorreo,
        ];

        try {
            $data = DB::select('EXEC acad.Sp_INS_estudiantes ?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            
            $data = DB::select('EXEC acad.Sp_SEL_estudiantes_personas ?,?', ['simple', $data[0]->iEstudianteId]);

            $response = ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        DB::commit();
        return new JsonResponse($response, $codeResponse);
    }

    public function searchEstudiantes(Request $request){
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
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function searchEstudiante(Request $request){
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
            $error_message = $this->parseSqlErrorService->parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function searchRepresentante(Request $request){
        $parametros = [
            'SIMPLE',
            $request->iEstudianteId,
            $request->iPersId,
            $request->cEstCodigo,
            $request->cPersNombre,
            $request->cPersPaterno,
            $request->cPersMaterno,
        ];

        try {
            $data = DB::select("execute acad.Sp_SEL_estudiante_representante ?,?,?,?,?,?,?", $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);

    }

    public function searchFamiliares(Request $request){
        $parametros = [
            'SIMPLE',
            $request->iEstudianteId,
            $request->iTipoFamiliarId,
            $request->iPersId,
            $request->cPersDocumento,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->cPersNombre,
        ];

        try {
            $data = DB::select("execute acad.Sp_SEL_estudiante_familiares ?,?,?,?,?,?,?", $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    private function validateGuardarPersona(Request $request){
        return $request->validate([
            'iTipoPersId' => 'required|integer',
            'iTipoIdentId' => 'required|integer',
            'cPersDocumento' => 'required|string|min:8|max:15',
            'cPersPaterno' => 'nullable|string|max:50',
            'cPersMaterno' => 'nullable|string|max:50',
            'cPersNombre' => 'required|string|max:50',
            'cPersSexo' => 'required|size:1',
            'dPersNacimiento' => 'nullable|date',
            'iTipoEstCivId' => 'nullable',
            'cPersFotografia' => 'nullable|string',
            'cPersRazonSocialCorto' => 'nullable|string|max:100',
            'cPersRazonSocialSigla' => 'nullable|string|max:50',
            'cPersDomicilio' => 'nullable|string',
            'iCredId' => 'nullable|integer',
        ]);
    }

    public function guardarRepresetantePersona(Request $request)
    {
        // primero guardar como persona
        $request->merge([
            'iTipoPersId' => 1, // Siempre persona natural
        ]);

        $this->validateGuardarPersona($request);
        $parametros = [
            
        ];

        DB::beginTransaction();

        try {
            $data = DB::select('EXEC grl.Sp_INS_personas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
            return new JsonResponse($response, $codeResponse);
        }

        // luego registrar como representante o estudiante-persona
        $parametros = [
            $request->iPersIdEstudiante,
            $data[0]->iPersId,
        ];

        try {
            $data = DB::select('EXEC acad.Sp_UPDATE_persona @_iPersId = ?, @_iPersRepresentanteId = ?', $parametros);
            $response = ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        DB::commit();
        return new JsonResponse($response, $codeResponse);
    }

    
    public function validarEstudiante(Request $request)
    {
        $request->validate([
            'iTipoIdentId' => 'required',
            'cPersDocumento' => 'required',
        ]);

        $parametros = [
            $request->iTipoIdentId,
            $request->cPersDocumento,
        ];

        try {
            $data = DB::select('exec grl.Sp_SEL_personasXiTipoIdentIdXcPersDocumento
                ?,?', $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        if( count($data) == 0){
            // No registra datos personales y tampoco estudiante
            // ¿Ejecutar consulta a servicio web RENIEC?
            // Por mientras proceder con registro manual
            $response = ['validated' => true, 'message' => 'No está registrado', 'data' => ['persona' => []]];
            $codeResponse = 200;
        } elseif( count($data) > 1 ) {
            // ERROR: mas de un registro con el mismo documento
            $response = ['validated' => false, 'message' => 'Documento de identidad duplicado', 'data' => ['persona' => $data]];
            $codeResponse = 500;
        } else {
            $parametros = [ $data[0]->iPersId ];
            $estudiante = DB::select('SELECT * FROM acad.estudiantes WHERE iPersId = ?', $parametros);
            $response = ['validated' => false, 'message' => 'Se obtuvo la información', 'data' => [ 'persona' => $data[0], 'estudiante' => count($estudiante) == 1 ? $estudiante[0] : []]];
            $codeResponse = 200;
        }
        return new JsonResponse($response, $codeResponse);
    }

    public function validarRepresentante(Request $request)
    {
        $request->validate([
            'iTipoIdentId' => 'required',
            'cPersDocumento' => 'required',
        ]);

        $parametros = [
            $request->iTipoIdentId,
            $request->cPersDocumento,
        ];

        try {
            $data = DB::select('exec grl.Sp_SEL_personasXiTipoIdentIdXcPersDocumento
                ?,?', $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        if( count($data) == 0){
            // No registra datos personales
            // ¿Ejecutar consulta a servicio web RENIEC?
            // Por mientras proceder con registro manual
            $response = ['validated' => true, 'message' => 'No se obtuvo la información', 'data' => ['persona' => []]];
            $codeResponse = 200;
        } elseif( count($data) > 1 ) {
            // ERROR: mas de un registro con el mismo documento
            $response = ['validated' => false, 'message' => 'Documento de identidad duplicado', 'data' => ['persona' => $data]];
            $codeResponse = 500;
        } else {
            $parametros = [ $data[0]->iPersId ];
            $response = ['validated' => false, 'message' => 'Se obtuvo la información', 'data' => [ 'persona' => $data[0]]];
            $codeResponse = 200;
        }
        return new JsonResponse($response, $codeResponse);
    }

    public function importarEstudiantesPadresExcel(Request $request)
    {
        $datos_hojas = $this->leerExcelService->leer($request);
        
        $datos_hoja = $this->formatearExcelPadresService->formatear($datos_hojas);

        $parametros = [
            $request->iSedeId,
            $request->iSemAcadId,
            $request->iYAcadId,
            $request->iCredId,
            $datos_hoja['nivel'],
            $datos_hoja['modalidad'],
            $datos_hoja['turno'],
            json_encode($datos_hoja['estudiantes']),
        ];

        // return $datos_hoja;

        try {
            $data = DB::select('EXEC acad.Sp_INS_estudiantes_padres_masivo ?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }
        
        return new JsonResponse($response, $codeResponse);
    }
}
