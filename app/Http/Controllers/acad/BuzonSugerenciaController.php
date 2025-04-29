<?php

namespace App\Http\Controllers\acad;

use App\Enums\Perfil;
use App\Http\Controllers\Controller;
use App\Models\acad\BuzonSugerencia;
use App\Services\ParseSqlErrorService;
use Exception;
use Hashids\Hashids;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class BuzonSugerenciaController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/acad/buzon-sugerencias",
     *     tags={"Buzón de sugerencias"},
     *     summary="Permite a un estudiante registrar una sugerencia en el buzón de sugerencias.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(ref="#/components/parameters/iCredEntPerfId"),
     *     @OA\Parameter(
     *         name="cAsunto",
     *         in="query",
     *         required=true,
     *         description="Asunto de la sugerencia",
     *         @OA\Schema(type="string", maxLength=255)
     *     ),
     *     @OA\Parameter(
     *         name="cSugerencia",
     *         in="query",
     *         required=true,
     *         description="Contenido de la sugerencia",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="iPrioridadId",
     *         in="query",
     *         required=true,
     *         description="ID de la prioridad de la sugerencia",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sugerencia registrada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Se ha registrado su sugerencia")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Problema al registrar",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="danger"),
     *             @OA\Property(property="message", type="string", example="Hubo un problema al registrar: [detalle del error]")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        Gate::authorize('tiene-perfil', [[Perfil::ESTUDIANTE]]);
        try {
            $usuario = Auth::user();
            BuzonSugerencia::registrarBuzon($request, $usuario);
            return response()->json(['status' => 'success', 'message' => 'Se ha registrado su sugerencia', 'data'=>$usuario], Response::HTTP_CREATED);
        } catch (Exception $ex) {
            return response()->json(['status' => 'danger', 'message' => 'Hubo un problema al registrar: ' . $ex->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     *
     * @OA\Get(
     *     path="/api/acad/buzon-sugerencias",
     *     tags={"Buzón de sugerencias"},
     *     summary="Permite a un estudiante obtener la lista de sus sugerencias registradas.",
     *     security={{"bearerAuth":{}}},
     *    @OA\Response(response=200,description="Sugerencia registrada exitosamente"),
     * )
     */
    public function index()
    {
        return response()->json(['status' => 'success', 'message' => 'Datos obtenidos'], Response::HTTP_OK);
    }
}
