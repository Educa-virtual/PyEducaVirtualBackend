<?php

namespace App\Http\Controllers\cap;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;
use App\Http\Controllers\grl\PersonasController;
use App\Http\Requests\bienestar\FichaRecreacionSaveRequest;
use App\Models\cap\Instructor;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class InstructoresController extends Controller
{
    private $ESTADO_ELIMINADO = 0;
    private $ESTADO_INACTIVO = 10;
    private $ESTADO_ACTIVO = 1;

    public function buscarInstructorxiTipoIdentIdxcPersDocumento($iTipoIdentId = 1, $cPersDocumento = null, Request $request)
    {
        $request->merge(['iTipoIdentId' => $iTipoIdentId]);
        $request->merge(['cPersDocumento' => $cPersDocumento]);

        // Validación de los parámetros de entrada
        $validator = Validator::make($request->all(), [
            'iTipoIdentId' => ['required'],
            'cPersDocumento' => ['required'],
        ], [
            'iTipoIdentId.required' => 'No se encontró el identificador iTipoIdentId',
            'cPersDocumento.required' => 'No se encontró el número del documento',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        try {
            $fieldsToDecode = [
                'iTipoIdentId',
                'iPersId'
            ];

            $data = new PersonasController();
            $data = $data->buscarPersonaxiTipoIdentIdxcPersDocumento($request);

            if (isset($data['data']['iPersId'])) {
                $request->merge(['iPersId' => $data['data']['iPersId']]);
                $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

                return new JsonResponse(
                    ['validated' => false, 'message' => 'Se obtuvo la información exitosamente', 'data' => $data['data']],
                    Response::HTTP_OK
                );
            } else {

                return new JsonResponse(
                    ['validated' => false, 'message' => 'Se obtuvo la información exitosamente', 'data' => $data['data']],
                    Response::HTTP_OK
                );
            }
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function listarInstructores(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iInstId',
                'iPersId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);
            $data = Instructor::selInstructores($request);
            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);
            return FormatearMensajeHelper::ok('Se ha obtenido exitosamente ', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarInstructores(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iTipoIdentId' => ['required'],
            'cPersDocumento' => ['required'],
            'cPersNombre' => ['required', 'string'],
            'cPersPaterno' => ['required'],
            'cPersMaterno' => ['required'],
            'cPersCelular' => ['required'],
            'cPersCorreo' => ['required', 'email'],
            'cPersDireccion' => ['required']
        ], [
            'iTipoIdentId.required' => 'No se encontró el identificador iTipoIdentId',
            'cPersDocumento.required' => 'No se encontró el número del documento',
            'cPersNombre.required' => 'Debe ingresar el nombre',
            'cPersNombre.string' => 'El nombre debe ser una cadena de texto',
            'cPersPaterno.required' => 'Debe ingresar el apellido paterno',
            'cPersMaterno.required' => 'Debe ingresar el apellido materno',
            'cPersCelular.required' => 'Debe ingresar el número de celular',
            'cPersCorreo.required' => 'Debe ingresar el correo electrónico',
            'cPersCorreo.email' => 'Debe ingresar un correo electrónico válido',
            'cPersDireccion.required' => 'Debe ingresar la dirección'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!isset($request->iPersId)) {
            $persona = new PersonasController();
            $persona = $persona->guardarPersonas($request);

            if ($persona[0]->iPersId > 0) {
                $request->merge(['iPersId' => $persona[0]->iPersId]);
                $request->merge(['dPersNacimiento' => null]);
                $request->merge(['cPersFotografia' => null]);
            } else {
                return response()->json([
                    'validated' => false,
                    'errors' => 'No se encontró el iPersId'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
        $request->merge(['cPersDomicilio' => $request->cPersDireccion]);
        $datosPersonales = new PersonasController();
        $datosPersonales = $datosPersonales->guardarPersonasxDatosPersonales($request);

        try {
            $data = Instructor::insInstructores($request);
            return FormatearMensajeHelper::ok('Se ha guardado exitosamente', $data);
            
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function eliminarInstructores(Request $request, $iInstId)
    {
        try {
            $request->merge(['iInstId' => $iInstId]);
            $request = VerifyHash::validateRequest($request, ['iInstId']);
            $data = Instructor::delInstructores($request);
            return FormatearMensajeHelper::ok('Se ha eliminado exitosamente', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarInstructores(Request $request, $iInstId)
    {
        $request->merge(['iInstId' => $iInstId]);
        $request = VerifyHash::validateRequest($request, ['iInstId']);

        $validator = Validator::make($request->all(), [
            'cOpcion' => ['required'],
            'iInstId' => ['required'],
            'cPersCelular' => ['required'],
            'cPersCorreo' => ['required', 'email'],
            'cPersDireccion' => ['required']
        ], [
            'cOpcion.required' => 'No se encontró la opción',
            'iInstId.required' => 'No se encontró el identificador iInstId',
            'cPersCelular.required' => 'Debe ingresar el número de celular',
            'cPersCorreo.required' => 'Debe ingresar el correo electrónico',
            'cPersCorreo.email' => 'Debe ingresar un correo electrónico válido',
            'cPersDireccion.required' => 'Debe ingresar la dirección'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = Instructor::updInstructores($request);
            return FormatearMensajeHelper::ok('Se ha actualizado exitosamente', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarEstadoInstructores($iInstId, Request $request)
    {
        try {
            Log::info($iInstId);
            $request->merge(['iInstId' => $iInstId]);
            $request = VerifyHash::validateRequest($request, ['iInstId']);
            $data = Instructor::updInstructoresEstado($request);
            return FormatearMensajeHelper::ok('Se ha actualizado exitosamente', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
