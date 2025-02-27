<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use App\Services\ParseSqlErrorService;
use Hashids\Hashids;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApoderadoController extends Controller
{
    protected $hashids;
    protected $parseSqlErrorService;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $this->parseSqlErrorService = new ParseSqlErrorService();
    }

    public function save(Request $request)
    {
        // primero guardar como persona
        $request->merge([
            'iTipoPersId' => 1, // Siempre persona natural
        ]);

        $parametros = [
            $request->iEstudianteId,
            $request->iTipoIdentId,
            $request->cPersDocumento,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->cPersNombre,
            $request->cPersSexo,
            $request->dPersNacimiento,
            $request->iTipoEstCivId,
            $request->cPersFotografia,
            $request->cPersDomicilio,
            $request->iCredId,
            $request->iNacionId,
            $request->iPaisId,
            $request->iDptoId,
            $request->iPrvnId,
            $request->iDsttId,
            $request->cPersContacto,
        ];

        try {
            $data = DB::select('EXEC acad.Sp_INS_apoderado ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = $this->parseSqlErrorService->parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }

    public function show(Request $request)
    {
        $parametros = [
            $request->iEstudianteId,
            $request->iPersId,
            $request->iApoderadoId,
            $request->cEstCodigo,
        ];

        try {
            $data = DB::select("execute acad.Sp_SEL_apoderado ?,?,?,?", $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = $this->parseSqlErrorService->parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function update(Request $request)
    {
        $parametros = [
            $request->iEstudianteId,
            $request->iPersApoderadoId,
            $request->iTipoIdentId,
            $request->cPersDocumento,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->cPersNombre,
            $request->cPersSexo,
            $request->dPersNacimiento,
            $request->iTipoEstCivId,
            $request->cPersFotografia,
            $request->cPersDomicilio,
            $request->iNacionId,
            $request->iPaisId,
            $request->iDptoId,
            $request->iPrvnId,
            $request->iDsttId,
            $request->iCredId,
            $request->cContacto,
        ];

        try {
            $data = DB::select('execute acad.Sp_UPD_apoderado ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = $this->parseSqlErrorService->__invoke($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

}
