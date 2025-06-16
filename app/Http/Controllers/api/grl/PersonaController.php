<?php

namespace App\Http\Controllers\api\grl;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Services\ConsultarDocumentoIdentidadService;
use App\Services\ParseSqlErrorService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Gate;

class PersonaController extends Controller
{
    private $parseSqlErrorService;
    //private $consultarDocumentoIdentidadService;

    public function __construct()
    {
        //$this->consultarDocumentoIdentidadService = new ConsultarDocumentoIdentidadService;
        //$this->parseSqlErrorService = new ParseSqlErrorService;
    }

    public function __invoke(Request $request)
    {
        return Pdf::loadView('imprimir', ['invoice' => 12312])
            ->setPaper('a4', 'landscape')
            ->name('your-invoice.pdf');
    }


    /**
     * Busca si la persona está registrada en la base de datos. Si no está, consulta en el servicio web.
     * @param Request $request
     * @return JsonResponse
     */
    public function buscarPersona(Request $request)
    {
        $parametros = [
            $request->iTipoIdentId,
            $request->cPersDocumento,
        ];

        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR, Perfil::ADMINISTRADOR_DREMO]]);
            $resultado = DB::select('exec grl.Sp_SEL_personasXiTipoIdentIdXcPersDocumento ?,?', $parametros);
            $cantidadResultados = count($resultado);
            if ($cantidadResultados == 0) {
                // No está registrado, consultar en servicio web
                $consultarDocumentoService = new ConsultarDocumentoIdentidadService();
                $dataServicio = $consultarDocumentoService->buscar($request->iTipoIdentId, $request->cPersDocumento);
                $data = $dataServicio['data'];
                $mensaje = $dataServicio['message'];
                $status = $dataServicio['status'];
            } elseif ($cantidadResultados > 1) {
                $data = null;
                $mensaje = 'El documento de identidad está duplicado';
                $status = 500;
            } else {
                $data = $resultado[0];
                $mensaje = 'El usuario ya está registrado';
                $status = 200;
            }
            return FormatearMensajeHelper::ok($mensaje, $data, $status);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }





    /**
     * Guarda una persona
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request)
    {
        $request->merge([
            'iTipoPersId' => 1, // Siempre persona natural
        ]);
        $parametros = $this->validateGuardarPersona($request);

        try {
            $data = DB::select('execute grl.Sp_INS_personas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = $this->parseSqlErrorService->__invoke($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    /**
     * Busca una persona segun parametros
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request)
    {
        $parametros = [
            $request->opcion,
            $request->iPersId,
            $request->iTipoPersId,
            $request->iTipoIdentId,
            $request->cPersDocumento,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->cPersNombre,
            $request->cPersSexo,
            $request->dPersNacimiento,
            $request->iTipoEstCivId,
            $request->iNacionId,
            $request->cPersFotografia,
            $request->cPersRazonSocialNombre,
            $request->cPersRazonSocialCorto,
            $request->cPersRazonSocialSigla,
            $request->iPersRepresentanteLegalId,
            $request->cPersDomicilio,
            $request->iTipoSectorId,
            $request->iPaisId,
            $request->iDptoId,
            $request->iPrvnId,
            $request->iDsttId,
            $request->iPersEstado,
            $request->cPersCodigoVerificacion,
            $request->cPersObs,
        ];

        try {
            $data = DB::select("execute grl.Sp_SEL_personas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?", $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = $this->parseSqlErrorService->__invoke($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }



    /**
     * Valida los parametros de guardar persona
     * @param Request $request
     * @return array
     */
    private function validateGuardarPersona(Request $request)
    {
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
            'iNacionId' => 'nullable|integer',
            'iPersRepresentanteLegalId' => 'nullable|integer',
            'cPersDomicilio' => 'nullable|string',
            'iPaisId' => 'nullable|integer',
            'iDptoId' => 'nullable|integer',
            'iPrvnId' => 'nullable|integer',
            'iDsttId' => 'nullable|integer',
            'bCrearFicha' => 'nullable|boolean',
        ]);
    }
}
