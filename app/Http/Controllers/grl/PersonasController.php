<?php

namespace App\Http\Controllers\grl;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AuditoriaConsultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use App\Http\Controllers\api\grl\PersonaController;
use App\Services\grl\PersonasService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PersonasController extends Controller
{
    protected $hashids;
    protected $iPersId;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function actualizarDatosPersonales(Request $request)
    {
        try {
            $usuario = Auth::user();
            PersonasService::actualizarDatosPersonales($usuario->iPersId, $request);
            return FormatearMensajeHelper::ok('Se han actualizado sus datos');
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
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
        if ($request->iPersId) {
            $iPersId = $this->hashids->decode($request->iPersId);
            $iPersId = count($iPersId) > 0 ? $iPersId[0] : $request->iPersId;
        }

        $parametros = [
            $request->opcion,
            $iPersId                            ?? NULL,
            $request->iTipoPersId               ?? NULL,
            $request->iTipoIdentId              ?? NULL,
            $request->cPersDocumento            ?? NULL,
            $request->cPersPaterno              ?? NULL,
            $request->cPersMaterno              ?? NULL,
            $request->cPersNombre               ?? NULL,
            $request->cPersSexo                 ?? NULL,
            $request->dPersNacimiento           ?? NULL,
            $request->iTipoEstCivId             ?? NULL,
            $request->iNacionId                 ?? NULL,
            $request->cPersFotografia           ?? NULL,
            $request->cPersRazonSocialNombre    ?? NULL,
            $request->cPersRazonSocialCorto     ?? NULL,
            $request->cPersRazonSocialSigla     ?? NULL,
            $request->iPersRepresentanteLegalId ?? NULL,
            $request->cPersDomicilio            ?? NULL,
            $request->iTipoSectorId             ?? NULL,
            $request->iPaisId                   ?? NULL,
            $request->iDptoId                   ?? NULL,
            $request->iPrvnId                   ?? NULL,
            $request->iDsttId                   ?? NULL,
            $request->iPersEstado               ?? NULL,
            $request->cPersCodigoVerificacion   ?? NULL,
            $request->cPersObs                  ?? NULL
        ];

        try {
            $data = DB::select("execute grl.Sp_SEL_personas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?", [$parametros]);

            foreach ($data as $key => $value) {
                $value->iPersId = $this->hashids->encode($value->iPersId);
            }

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function obtenerPersonasxiPersId(Request $request)
    {
        $fieldsToDecode = [
            'iPersId',
        ];

        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);
        $parametros = [
            $request->iPersId
        ];

        try {
            $data = DB::select("execute grl.Sp_SEL_personasxiPersId ?", $parametros);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function guardarPersonasxDatosPersonales(Request $request)
    {
        $fieldsToDecode = [
            'iPersId',
        ];
        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->iPersId,
            $request->dPersNacimiento,
            $request->cPersFotografia,
            $request->cPersDomicilio,
            $request->cPersCorreo,
            $request->cPersCelular
        ];

        try {
            $data = DB::select("execute grl.Sp_UPD_personasxDatosPersonales ?,?,?,?,?,?", $parametros);

            if ($data[0]->iPersId > 0) {

                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function buscarPersonaxiTipoIdentIdxcPersDocumento(Request $request)
    {
        try {
            $fieldsToDecode = ['iTipoIdentId', 'iCredId'];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);
            $data = new PersonaController();
            $data = ($data->validate($request))->getContent();

            $data = json_decode($data, true);

            if (isset($data['data']['iPersId'])) {

                $request->merge(['iPersId' => $data['data']['iPersId']]);
                $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

                $contacto = new PersonasContactosController();

                $contacto = $contacto->obtenerxiPersId($request)->getData(true);
                $contacto = $contacto['data'];
                $data['data']['cPersDireccion'] = $contacto['cPersDireccion'];
                $data['data']['cPersCel'] = $contacto['cPersCel'];
                $data['data']['cPersCorreo'] = $contacto['cPersCorreo'];
                $data['data']['cPersTel'] = $contacto['cPersTel'];
                $data['data']['cPersRedSoc'] = $contacto['cPersRedSoc'];
            }
            return $data;
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function guardarPersonas(Request $request)
    {
        $fieldsToDecode = [
            'iPersId',
        ];
        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->iTipoIdentId          ??  NULL,
            $request->cPersDocumento        ??  NULL,
            $request->cPersNombre           ??  NULL,
            $request->cPersPaterno          ??  NULL,
            $request->cPersMaterno          ??  NULL
        ];

        try {
            $data = DB::select("execute grl.Sp_INS_personasGral ?,?,?,?,?", $parametros);
            return $data;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }
}
