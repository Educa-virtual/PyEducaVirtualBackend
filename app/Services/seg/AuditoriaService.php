<?php

namespace App\Services\seg;

use App\Http\Requests\seg\AuditoriaFiltroFechaRequest;
use App\Models\seg\Auditoria;
use Illuminate\Http\Request;

class AuditoriaService
{
    public static function obtenerAccesosAutorizados(AuditoriaFiltroFechaRequest $request)
    {
        return Auditoria::selAuditoriaAccesosAutorizados($request->filtroFechaInicio, $request->filtroFechaFin);
    }

    public static function obtenerAccesosFallidos(AuditoriaFiltroFechaRequest $request)
    {
        return Auditoria::selAuditoriaAccesosFallidos($request->filtroFechaInicio, $request->filtroFechaFin);
    }

    public static function obtenerConsultasDatabase(AuditoriaFiltroFechaRequest $request)
    {
        return Auditoria::selAuditoriaConsultasDatabase($request->filtroFechaInicio, $request->filtroFechaFin);
    }

    public static function obtenerConsultasBackend(AuditoriaFiltroFechaRequest $request)
    {
        return Auditoria::selAuditoriaConsultasBackend($request->filtroFechaInicio, $request->filtroFechaFin);
    }
}
