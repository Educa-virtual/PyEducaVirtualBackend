<?php

namespace App\Http\Controllers\cap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use Illuminate\Http\Response;
use App\Http\Controllers\api\grl\PersonaController;

class InscripcionesController extends Controller
{
    public function listarPersonaInscripcion(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iTipoIdentId',
                'iCredId',
            ];
            $request =  VerifyHash::validateRequest($request, $fieldsToDecode);
            
            $persona = new PersonaController();
            $persona = $persona->validate($request)->getData(true);
            return $persona['data'];

        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
