<?php

namespace App\Http\Controllers\acad;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Helpers\VerifyHash;
use App\Http\Requests\acad\ActualizarCalendarioAcademicosRequest;
use App\Http\Requests\acad\GuardarCalendarioAcademicosRequest;
use App\Models\acad\CalendarioAcademico;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class CalendarioAcademicosController extends Controller
{
    public function obtenerCalendarioAcademicosxiSedeIdxiYAcadId(Request $request, $iYAcadId, $iSedeId)
    {
        $request->merge([
            'iYAcadId' => $iYAcadId,
            'iSedeId' => $iSedeId,
        ]);

        $validator = Validator::make($request->all(), [
            'iYAcadId' => ['required'],
            'iSedeId' => ['required'],
        ], [
            'iYAcadId.required' => 'No se encontró el identificador iYAcadId',
            'iSedeId.required' => 'No se encontró el identificador iSedeId',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $fieldsToDecode = [
            'iYAcadId',
            'iSedeId',
            'iCredId',
        ];

        $request = VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->iYAcadId ?? null,
            $request->iSedeId ?? null,
            $request->iCredId ?? null,
        ];

        try {
            $data = DB::select(
                'EXEC [acad].[Sp_SEL_calendarioAcademicosxiYAcadIdxiSedeId] 
                    @_iYAcadId=?,
                    @_iSedeId=?,
                    @_iCredId=?',
                $parametros
            );
            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = Response::HTTP_OK;

            return $response;
        } catch (\Exception $e) {
            // Manejo de excepción y respuesta de error
            $response = [
                'validated' => false,
                'message' => $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine(),
                'data' => [],
            ];
            $estado = Response::HTTP_INTERNAL_SERVER_ERROR;

            return new JsonResponse($response, $estado);
        }
    }

    public function guardarCalendarioAcademicos(GuardarCalendarioAcademicosRequest $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO, Perfil::DIRECTOR_IE]]);
            $data = CalendarioAcademico::insCalendarioAcademicos($request);
            return FormatearMensajeHelper::ok('Se ha guardado exitosamente ', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarCalendarioAcademicos(ActualizarCalendarioAcademicosRequest $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO, Perfil::DIRECTOR_IE]]);
            $data = CalendarioAcademico::updCalendarioAcademicos($request);
            return FormatearMensajeHelper::ok('Se ha actualizado exitosamente ', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verCalendarioAcademicos(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO, Perfil::DIRECTOR_IE]]);
            $data = CalendarioAcademico::selCalendarioAcademicos($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch(\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function obtenerCalendario(Request $request)
    {
        // Renombrar y mover a otro lado, es calendario de clases
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DOCENTE,Perfil::ESTUDIANTE,Perfil::DIRECTOR_IE]]);
            $data = CalendarioAcademico::selCalendarioAcademico($request);

            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
