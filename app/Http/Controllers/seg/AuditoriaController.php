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
  public function response($query)
  {
    $response = [
      'validated' => true,
      'message' => '',
      'data' => [],
    ];
    $estado = 200;

    try {
      $response['message'] = 'Se obtuvo la información';
      $response['data'] = $query;
    } catch (Exception $e) {
      $response['message'] = $e->getMessage();
      $estado = 500;
    }

    return new JsonResponse($response, $estado);
  }

  public function sel_auditoria_accesos(Request $request)
  {
    $query = collect(DB::select('EXEC grl.SP_SEL_DesdeTablaOVista ?,?,?', [
      'seg',
      'V_' . $request->table,
      '*',
    ]))->sortByDesc('dtFecha')->values();
    return $this->response($query);
  }

  public function sel_auditoria_accesos_fallidos() {
    $query = collect(DB::select('EXEC grl.SP_SEL_DesdeTablaOVista ?,?,?,?', [
      'seg',
      'V_CalendariosAcademicos',
      '*',
    ]))->sortByDesc('cYearNombre')->values();
    return $this->response($query);
  }
  public function sel_auditoria() {
    $query = collect(DB::select('EXEC grl.SP_SEL_DesdeTablaOVista ?,?,?,?', [
      'seg',
      'V_CalendariosAcademicos',
      '*',
    ]))->sortByDesc('cYearNombre')->values();
    return $this->response($query);
  }
  public function sel_auditoria_backend() {
    $query = collect(DB::select('EXEC grl.SP_SEL_DesdeTablaOVista ?,?,?,?', [
      'seg',
      'V_CalendariosAcademicos',
      '*',
    ]))->sortByDesc('cYearNombre')->values();
    return $this->response($query);
  }
}
