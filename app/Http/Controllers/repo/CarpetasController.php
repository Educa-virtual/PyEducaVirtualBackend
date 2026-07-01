<?php

namespace App\Http\Controllers\repo;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\repo\ActualizarCarpetaRequest;
use App\Http\Requests\repo\GuardarCarpetaRequest;
use App\Models\repo\Carpeta;

class CarpetasController extends Controller
{
    public function listarCarpetas(Request $request)
    {
        try {
            $data = Carpeta::selCarpetas($request);
            return FormatearMensajeHelper::ok('Se ha obtenido exitosamente ', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verCarpeta(Request $request)
    {
        try {
            $data = Carpeta::selCarpeta($request);
            return FormatearMensajeHelper::ok('Se ha obtenido exitosamente ', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarCarpeta(ActualizarCarpetaRequest $request)
    {
        try {
            $data = Carpeta::updCarpeta($request);
            if ($data->iCarpetaId > 0) {
                return FormatearMensajeHelper::ok('Se ha actualizado exitosamente ', $data);
            } else {
                throw new \Exception('No se ha podido actualizar', 500);
            }
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarCarpeta(GuardarCarpetaRequest $request)
    {
        try {
            $data = Carpeta::insCarpeta($request);
            if ($data->iCarpetaId > 0) {
                return FormatearMensajeHelper::ok('Se ha guardado exitosamente ', $data);
            } else {
                throw new \Exception('No se ha podido guardar', 500);
            }
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function eliminarCarpeta(Request $request)
    {
        try {
            $data = Carpeta::delCarpeta($request);
            if ($data->iCarpetaId > 0) {
                return FormatearMensajeHelper::ok('Se ha eliminado exitosamente ', $data);
            } else {
                throw new \Exception('No se ha podido eliminar', 500);
            }
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
