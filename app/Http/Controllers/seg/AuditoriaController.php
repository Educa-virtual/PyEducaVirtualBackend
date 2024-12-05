<?php

namespace App\Http\Controllers\seg;

use Exception;
use App\Mail\CodigoMail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class AuditoriaController extends Controller
{
  public const schema = 'seg';

  public function selAuditoriaAccesos(Request $request)
  {
    $where = 'CAST(dtFecha as DATE) >= CAST('. "'" . $request->filtroFechaInicio . "'" .' as DATE) AND CAST(dtFecha as DATE) <= CAST('. "'" . $request->filtroFechaFin . "'" .' as DATE)';

    $query = parent::selDesdeTablaOVista(self::schema, 'V_auditoria_accesos', '*', $where)->sortByDesc('dtFecha')->values();

    return parent::response($query);
  }
  
  public function selAuditoriaAccesosFallidos(Request $request)
  {
    $where = 'CAST(dtFecha as DATE) >= CAST('. "'" . $request->filtroFechaInicio . "'" .' as DATE) AND CAST(dtFecha as DATE) <= CAST('. "'" . $request->filtroFechaFin . "'" .' as DATE)';

    $query = parent::selDesdeTablaOVista(self::schema, 'V_auditoria_accesos_fallidos', '*', $where)->sortByDesc('dtFecha')->values();

    return parent::response($query);
  }

  public function selAuditoria(Request $request)
  {
    $where = 'CAST(dtFecha as DATE) >= CAST('. "'" . $request->filtroFechaInicio . "'" .' as DATE) AND CAST(dtFecha as DATE) <= CAST('. "'" . $request->filtroFechaFin . "'" .' as DATE)';

    $query = parent::selDesdeTablaOVista(self::schema, 'V_auditoria', '*', $where)->sortByDesc('dtFecha')->values();

    return parent::response($query);
  }

  public function selAuditoriaMiddleware(Request $request)
  {
    $where = 'CAST(dtFecha as DATE) >= CAST('. "'" . $request->filtroFechaInicio . "'" .' as DATE) AND CAST(dtFecha as DATE) <= CAST('. "'" . $request->filtroFechaFin . "'" .' as DATE)';

    $query = parent::selDesdeTablaOVista(self::schema, 'V_auditoria_middleware', '*', $where)->sortByDesc('dtFecha')->values();

    return parent::response($query);
  }
}
