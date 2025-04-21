<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;

class AnunciosController extends Controller
{
    public function guardarAnuncios(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iCursosNivelGradId',
                'idDocCursoId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);
            
            $parametros = [
                $request->iCursosNivelGradId          ??  NULL,
                $request->idDocCursoId                ??  NULL,
                $request->cTitulo                     ??  NULL,
                $request->cContenido                  ??  NULL,
                $request->iCredId                     ??  NULL
            ];
            
            $data = DB::select(
                'exec aula.SP_INS_anuncios 
                    @_iCursosNivelGradId=?, 
                    @_idDocCursoId=?, 
                    @_cTitulo=?, 
                    @_cContenido=?, 
                    @_iCredId=?',
                $parametros
            );
            if ($data[0]->iAnuncioId > 0) {
                return new JsonResponse(
                    ['validated' => true, 'message' => 'Se ha guardado exitosamente ', 'data' => null],
                    Response::HTTP_OK
                );
            } else {
                return new JsonResponse(
                    ['validated' => false, 'message' => 'No se ha podido guardar', 'data' => null],
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

    public function listarAnuncios(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iAnuncioId',
                'iCursosNivelGradId',
                'idDocCursoId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);
            $parametros = [
                $request->iCursosNivelGradId          ??  NULL,
                $request->idDocCursoId                ??  NULL,
                $request->iCredId                     ??  NULL
            ];

            $data = DB::select(
                'exec aula.SP_SEL_anuncios 
                    @_iCursosNivelGradId=?, 
                    @_idDocCursoId=?, 
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

    public function eliminarAnuncios(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iAnuncioId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);
            $parametros = [
                $request->iAnuncioId                  ??  NULL,
                $request->iCredId                     ??  NULL
            ];

            $data = DB::select(
                'exec aula.SP_DEL_anuncios 
                    @_iAnuncioId=?, 
                    @_iCredId=?',
                $parametros
            );
            if ($data[0]->iAnuncioId > 0) {
                return new JsonResponse(
                    ['validated' => true, 'message' => 'Se ha eliminado exitosamente ', 'data' => null],
                    Response::HTTP_OK
                );
            } else {
                return new JsonResponse(
                    ['validated' => false, 'message' => 'No se ha podido eliminar', 'data' => null],
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

    public function fijarAnuncios(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iAnuncioId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);
            $parametros = [
                $request->iAnuncioId                  ??  NULL,
                $request->iCredId                     ??  NULL
            ];

            $data = DB::select(
                'exec aula.SP_UPD_anunciosFijarxiAnuncioId 
                    @_iAnuncioId=?, 
                    @_iCredId=?',
                $parametros
            );
            if ($data[0]->iAnuncioId > 0) {
                return new JsonResponse(
                    ['validated' => true, 'message' => 'Se ha fijado exitosamente ', 'data' => null],
                    Response::HTTP_OK
                );
            } else {
                return new JsonResponse(
                    ['validated' => false, 'message' => 'No se ha podido fijar', 'data' => null],
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
