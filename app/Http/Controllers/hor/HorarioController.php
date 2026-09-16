<?php

namespace App\Http\Controllers\hor;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Services\acad\MatriculasService;
use App\Services\hor\HorariosService;
use App\Services\seg\UsuariosService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HorarioController extends Controller
{
    public function obtenerHorario($iYAcadId, Request $request)
    {
        try {
            $detallesCredencial = UsuariosService::obtenerDetallesCredencialEntidad($request->header('iCredEntPerfId'));
            $params = [Auth::user()->iPersId, $iYAcadId, $detallesCredencial->iSedeId, null];
            $matricula = MatriculasService::obtenerDetalleMatriculaEstudiante($params);
            $horario = HorariosService::obtenerHorario($matricula);

            return FormatearMensajeHelper::ok('Datos obtenidos', ['matricula' => $matricula, 'horario' => $horario]);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function selHorario(Request $request){
        try {
            $parametros = [
                $request->iSedeId ?? null,
            ];

            $data = DB::select(
            'SELECT
            iConfBloqueId
            ,cDescripcion
            ,iNumBloque
            ,iBloqueInter
            ,iEstado
            ,tInicio
            ,tFin
            FROM hor.configuracion_bloques WHERE iSedeId = ?',
                $parametros
            );

            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function insHorario(Request $request){
        try {
            $solicitud = [
                $request->iConfBloqueId ?? NULL,
                $request->iNumBloque ?? NULL,
                $request->iBloqueInter ?? NULL,
                $request->cDescripcion ?? NULL,
                $request->tInicio ?? NULL,
                $request->tFin ?? NULL,
                $request->iSedeId ?? NULL,
                $request->header('iCredEntPerfId') ?? NULL,
            ];
      
            $parametros = str_repeat('?,', count($solicitud) - 1).'?';

            $query = DB::select(
                'EXEC hor.SP_INS_configurarBloque '.$parametros,
                $solicitud
            );

            return FormatearMensajeHelper::ok('Se obtuvo la información', $query);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function insDetalleBloque(Request $request){
        try {
            $solicitud = [
                $request->iConfBloqueId ?? NULL,
                $request->iBloqueId ?? NULL,
                $request->tBloqueInicio ?? NULL,
                $request->tBloqueFin ?? NULL,
                $request->header('iCredEntPerfId') ?? NULL,
            ];
      
            $parametros = str_repeat('?,', count($solicitud) - 1).'?';

            $query = DB::select(
                'EXEC hor.SP_INS_configurarDetalleBloque '.$parametros,
                $solicitud
            );

            return FormatearMensajeHelper::ok('Se obtuvo la información', $query);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
