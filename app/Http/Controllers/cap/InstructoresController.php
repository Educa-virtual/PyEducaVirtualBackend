<?php

namespace App\Http\Controllers\cap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;
use App\Http\Controllers\grl\PersonasController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InstructoresController extends Controller
{
    //Notas: Campo iEstado
    // 0 => Eliminado
    // 1 => Activo
    // 10 => Inactivo

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

            $parametros = [
                $request->iEstado              ??  NULL,
                $request->iCredId              ??  NULL
            ];

            $data = DB::select(
                'exec cap.SP_SEL_instructores 
                    @_iEstado=?,   
                    @_iCredId=?',
                $parametros
            );
            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);
            return new JsonResponse(
                ['validated' => true, 'message' => 'Se ha obtenido exitosamente ', 'data' => ($data)],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
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
                $datosPersonales = new PersonasController();
                $datosPersonales = $datosPersonales->guardarPersonasxDatosPersonales($request);
                $request->merge(['iPersId' => $persona[0]->iPersId]);
            } else {
                return response()->json([
                    'validated' => false,
                    'errors' => 'No se encontró el iPersId'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        try {
            $fieldsToDecode = [
                'iPersId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iPersId               ??  NULL,
                $request->iCredId               ??  NULL
            ];

            $data = DB::select(
                'exec cap.SP_INS_instructores 
                    @_iPersId=?,   
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iInstId > 0) {
                $message = 'Se ha guardado exitosamente';
                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => []],
                    Response::HTTP_OK
                );
            } else {
                $message = 'No se ha podido guardar';
                return new JsonResponse(
                    ['validated' => false, 'message' => $message, 'data' => []],
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

    public function eliminarInstructores(Request $request, $iInstId)
    {
        $request->merge(['iInstId' => $iInstId]);

        $validator = Validator::make($request->all(), [
            'iInstId' => ['required'],
        ], [
            'iInstId.required' => 'No se encontró el identificador iInstId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iInstId',
                'iCredId'
            ];

            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iInstId      ??  NULL,
                $request->iCredId      ??  NULL
            ];
            $data = DB::select(
                'exec cap.SP_DEL_instructores
                    @_iInstId=?,    
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iInstId > 0) {
                $message = 'Se ha eliminado exitosamente';
                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => $data],
                    Response::HTTP_OK
                );
            } else {
                $message = 'No se ha podido eliminar';
                return new JsonResponse(
                    ['validated' => false, 'message' => $message, 'data' => []],
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

    public function actualizarInstructores(Request $request, $iInstId)
    {
        $request->merge(['iInstId' => $iInstId]);
         
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
            $fieldsToDecode = [
                'iInstId',
                'iCredId'
            ];

            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->cOpcion           ??  NULL,
                $request->iInstId           ??  NULL,
                $request->cPersCelular      ??  NULL,
                $request->cPersCorreo       ??  NULL,
                $request->cPersDireccion    ??  NULL,
                $request->iCredId           ??  NULL
            ];
           
            $data = DB::select(
                'exec cap.SP_UPD_instructores
                    @_cOpcion=?,    
                    @_iInstId=?,    
                    @_cPersCelular=?,    
                    @_cPersCorreo=?,    
                    @_cPersDireccion=?,    
                    @_iCredId=?',
                $parametros
            );
         
            if ($data[0]->iInstId > 0) {
                $message = 'Se ha actualizado exitosamente';
                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => $data],
                    Response::HTTP_OK
                );
            } else {
                $message = 'No se ha podido actualizar';
                return new JsonResponse(
                    ['validated' => false, 'message' => $message, 'data' => []],
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

    public function cambiarEstadoInstructores(Request $request, $iInstId)
    {
        $request->merge(['iInstId' => $iInstId]);

        $validator = Validator::make($request->all(), [
            'iInstId' => ['required'],
        ], [
            'iInstId.required' => 'No se encontró el identificador iInstId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $fieldsToDecode = [
                'iInstId',
                'iCredId'
            ];

            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

            $parametros = [
                $request->iInstId      ??  NULL,
                $request->iCredId      ??  NULL
            ];
            $data = DB::select(
                'exec cap.SP_DEL_instructores
                    @_iInstId=?,    
                    @_iCredId=?',
                $parametros
            );

            if ($data[0]->iInstId > 0) {
                $message = 'Se ha eliminado exitosamente';
                return new JsonResponse(
                    ['validated' => true, 'message' => $message, 'data' => $data],
                    Response::HTTP_OK
                );
            } else {
                $message = 'No se ha podido eliminar';
                return new JsonResponse(
                    ['validated' => false, 'message' => $message, 'data' => []],
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
}
