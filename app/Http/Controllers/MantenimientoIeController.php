<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MantenimientoIe;
use Illuminate\Http\Request;
use App\Services\Acad\MantenimientoIeService;
use Illuminate\Support\Facades\Log;

class MantenimientoIeController extends Controller
{
    protected $mantenimientoIeService;

    public function __construct(MantenimientoIeService $mantenimientoIeService)
    {
        $this->mantenimientoIeService = $mantenimientoIeService;
    }

    /**
     * Obtener instituciones educativas 
     */
    public function obtenerInstiucionEducativa(Request $request)
    {
        $parametros = [
            $request->iDsttId,
            $request->iZonaId,
            $request->iTipoSectorId,
            $request->iNivelTipoId,
            $request->iUgelId,
            $request->termino_busqueda,
            $request->pagina ?? 1,
            $request->registros_por_pagina ?? 20,
            $request->sedes ?? null,
            0
        ];

        try {
            $data = $this->mantenimientoIeService->listarInstituciones($parametros);
            $response = [
                'validated' => true,
                'mensaje' => 'Se obtuvo la información correctamente',
                'data' => $data
            ];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = MantenimientoIe::parse($e->getMessage());
            $response = [
                'validated' => false,
                'mensaje' => $error_message
            ];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    /**
     * Crear nueva institución educativa
     */
    public function crearInstitucionEducativa(Request $request)
    {
        $parametros = [
            $request->cIieeCodigoModular,
            $request->iDsttId,
            $request->iZonaId,
            $request->iTipoSectorId,
            $request->cIieeNombre,
            $request->cIieeRUC,
            $request->cIieeDireccion,
            $request->cIieeLogo,
            $request->iNivelTipoId,
            $request->iUgelId,
            $request->iSesionId,
            //$request->iSedeId
        ];

        try {
            $data = $this->mantenimientoIeService->crear($parametros);
            $response = [
                'validated' => true,
                'mensaje' => 'Institución educativa creada correctamente',
                'data' => $data[0] ?? null
            ];
            $codeResponse = 201;
        } catch (\Exception $e) {
            $error_message = MantenimientoIe::parse($e->getMessage());
            $response = [
                'validated' => false,
                'mensaje' => $error_message
            ];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    /**
     * Actualizar institución educativa
     */
    public function actualizarInstitucionEducativa(Request $request, $id)
    {
        $parametros = [
            $id,
            $request->cIieeCodigoModular,
            $request->iDsttId,
            $request->iZonaId,
            $request->iTipoSectorId,
            $request->cIieeNombre,
            $request->cIieeRUC,
            $request->cIieeDireccion,
            $request->cIieeLogo,
            $request->iNivelTipoId,
            $request->iUgelId,
            $request->iSesionId
        ];

        try {
            $data = $this->mantenimientoIeService->actualizar($parametros);
            $response = [
                'validated' => true,
                'mensaje' => 'Institución educativa actualizada correctamente',
                'data' => $data[0] ?? null
            ];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = MantenimientoIe::parse($e->getMessage());
            $response = [
                'validated' => false,
                'mensaje' => $error_message
            ];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    /**
     * Eliminar institución educativa (lógico)
     */
    public function eliminarInstitucionEducativa(Request $request, $id)
    {
        Log::info("este es el" . $id);
        $parametros = [
            $id,
            $request->iSesionId
        ];
        try {
            $data = $this->mantenimientoIeService->eliminar($parametros);
            $response = [
                'validated' => true,
                'mensaje' => 'Institución educativa eliminada correctamente',
                'data' => $data[0] ?? null
            ];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = MantenimientoIe::parse($e->getMessage());
            $response = [
                'validated' => false,
                'mensaje' => $error_message
            ];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }
}
