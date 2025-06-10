<?php

namespace App\Http\Controllers\acad;

use App\Enums\Perfil;
use App\Helpers\FormatearErrorHelper;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\acad\EliminarSugerenciaRequest;
use App\Http\Requests\acad\RegistrarSugerenciaRequest;
use App\Http\Requests\GeneralFormRequest;
use App\Models\acad\BuzonSugerencia;
use App\Services\acad\BuzonSugerenciasService;
use App\Services\ParseSqlErrorService;
use Exception;
use Hashids\Hashids;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class BuzonSugerenciaController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/acad/estudiantes/buzon-sugerencias",
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
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Se ha registrado su sugerencia"),
     *             @OA\Property(property="data", type="int", example="ID de la sugerencia registrada")
     *         )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/400"),
     *     @OA\Response(response=403, ref="#/components/responses/403"),
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     * )
     */
    public function registrarSugerencia(RegistrarSugerenciaRequest $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ESTUDIANTE]]);
            $data = BuzonSugerenciasService::registrarSugerencia($request);
            return FormatearMensajeHelper::ok('Se ha registrado su sugerencia', $data, Response::HTTP_CREATED);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/acad/estudiantes/buzon-sugerencias/{iSugerenciaId}/archivos",
     *     tags={"Buzón de sugerencias"},
     *     summary="Obtiene la lista de archivos subidos para una sugerencia.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(ref="#/components/parameters/iCredEntPerfId"),
     *     @OA\Parameter(
     *         name="iSugerenciaId",
     *         in="path",
     *         required=true,
     *         description="Id de la sugerencia",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sugerencia registrada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Se ha registrado su sugerencia"),
     *             @OA\Property(property="data", type="int", example="ID de la sugerencia registrada")
     *         )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/400"),
     *     @OA\Response(response=403, ref="#/components/responses/403"),
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     * )
     */
    public function obtenerArchivosSugerencia($iSugerenciaId)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ESTUDIANTE, Perfil::DIRECTOR_IE]]);
            $data = BuzonSugerencia::obtenerArchivosSugerencia($iSugerenciaId);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/acad/estudiantes/buzon-sugerencias",
     *     tags={"Buzón de sugerencias"},
     *     summary="Lista las sugerencias registradas por el alumno, que no estén marcadas como eliminadas.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(ref="#/components/parameters/iCredEntPerfId"),
     *     @OA\Response(
     *         response=200,
     *         description="Datos obtenidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Datos obtenidos"),
     *             @OA\Property(property="data", type="object", example="Lista de sugerencias")
     *         )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/400"),
     *     @OA\Response(response=403, ref="#/components/responses/403"),
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     * )
     */
    public function obtenerListaSugerenciasEstudiante(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ESTUDIANTE]]);
            $data = BuzonSugerenciasService::obtenerSugerenciasEstudiante($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function obtenerListaSugerenciasDirector(Request $request) {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);
            $data = BuzonSugerenciasService::obtenerSugerenciasDirector($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/acad/estudiantes/buzon-sugerencias/{iCredEntPerfId}",
     *     tags={"Buzón de sugerencias"},
     *     summary="Marca la sugerencia de un alumno como eliminada.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="iSugerenciaId",
     *         in="path",
     *         required=true,
     *         description="Id de la sugerencia",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/iCredEntPerfId"),
     *     @OA\Response(
     *         response=200,
     *         description="Sugerencia eliminada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success")
     *         )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/400"),
     *     @OA\Response(response=403, ref="#/components/responses/403"),
     *     @OA\Response(response=422, ref="#/components/responses/422"),
     * )
     */
    public function eliminarSugerencia($iSugerenciaId, Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ESTUDIANTE]]);
            BuzonSugerencia::delBuzonSugerencias($iSugerenciaId, $request);
            return FormatearMensajeHelper::ok('Success', null, Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function descargarArchivosSugerencia($iSugerenciaId, $nombreArchivo)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ESTUDIANTE, Perfil::DIRECTOR_IE]]);
            $data = BuzonSugerenciasService::descargarArchivo($iSugerenciaId, $nombreArchivo);
            return response($data['contenido'], 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $data['nombreArchivo'] . '"'
            ]);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
            //abort(Response::HTTP_NOT_FOUND);
        }
    }
}
