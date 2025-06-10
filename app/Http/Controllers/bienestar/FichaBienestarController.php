<?php

namespace App\Http\Controllers\bienestar;

use App\Http\Controllers\Controller;
use App\Services\ParseSqlErrorService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FichaBienestarController extends Controller
{
    /**
     * Obtiene una lista de estudiantes asociados a un apoderado
     * @param Request $request contiene el perfil y el año académico
     * @return JsonResponse respuesta con los datos de los estudiantes
     */
    public function listarEstudiantesApoderado(Request $request)
    {
        $parametros = [
            $request->iCredEntPerfId,
            $request->iYAcadId,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_SEL_fichasApoderado ?,?', $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        }
        catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }

    /**
     * Obtiene una lista de fichas según los parámetros proporcionados
     * @param Request $request contiene los parámetros de búsqueda
     * @return JsonResponse respuesta con el estado de la operación y los datos obtenidos
     */
    public function listarFichas(Request $request)
    {
        $parametros = [
            $request->iCredSesionId,
            $request->iFichaDGId,
            $request->iPersId,
            $request->cPersDocumento,
            $request->cPersNombresApellidos,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_SEL_fichas ?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        }
        catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }

    /**
     * Si se acepta la declaración jurada, se crea la ficha en blanco
     * @param Request $request envia año académico e id de persona
     * @return JsonResponse contiene el id de la ficha creada
     */
    public function crearFicha(Request $request)
    {
        $parametros = [
            $request->iYAcadId,
            $request->iPersId,
        ];

        try {
            DB::select('EXEC obe.Sp_INS_ficha ?,?', $parametros);
            $response = ['validated' => true, 'message' => 'se guardo la información', 'data' => []];
            $codeResponse = 200;
        }
        catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }

    /**
     * Obtiene los parámetros necesarios para registrar una ficha
     * @param Request $request no se usa, pero se mantiene por compatibilidad
     * @return JsonResponse contiene los parámetros para registrar una ficha
     */
    public function obtenerParametrosFicha(Request $request)
    {
        try {
            $data = DB::select('EXEC obe.Sp_SEL_fichaParametros');
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        }
        catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }

    /**
     * Elimina una ficha
     * @param Request $request contiene el id de la ficha a eliminar
     * @return JsonResponse respuesta con el estado de la operación
     */
    public function borrarFicha(Request $request)
    {
        $parametros = [
            $request->iFichaDGId,
        ];

        try {
            DB::select('EXEC obe.Sp_DEL_ficha ?', $parametros);
            $response = ['validated' => true, 'message' => 'se elimino la ficha', 'data' => []];
            $codeResponse = 200;
        }
        catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }

    /**
     * Obtiene los datos generales de una ficha específica segun id de ficha o persona
     * @param Request $request contiene el id de la ficha, id de la persona y año académico
     * @return JsonResponse respuesta con los datos generales de la ficha
     */
    public function verFicha(Request $request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->iPersId,
            $request->iYAcadId,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_SEL_ficha ?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        }
        catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }
}
