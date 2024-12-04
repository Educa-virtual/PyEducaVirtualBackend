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

  public function selAuditoriaAccesos()
  {
    $query = parent::selDesdeTablaOVista(self::schema, 'V_auditoria_accesos', '*')->sortByDesc('dtFecha')->values();

    return parent::response($query);
  }
  
  public function selAuditoriaAccesosFallidos()
  {
    $query = parent::selDesdeTablaOVista(self::schema, 'V_auditoria_accesos_fallidos', '*')->sortByDesc('dtFecha')->values();

    return parent::response($query);
  }

  public function selAuditoria()
  {
    $query = parent::selDesdeTablaOVista(self::schema, 'V_auditoria', '*')->sortByDesc('dtFecha')->values();

    return parent::response($query);
  }

  public function selAuditoriaMiddleware()
  {
    $query = parent::selDesdeTablaOVista(self::schema, 'V_auditoria_middleware', '*')->sortByDesc('dtFecha')->values();

    return parent::response($query);
  }
}
