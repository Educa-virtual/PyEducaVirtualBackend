<?php

namespace App\Http\Controllers\seg;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;

class AuditoriaAccesosController extends Controller
{
  public const schema = 'seg';

  public function selAuditoriaAccesos(Request $request)
  {
    try {
      // Validar los datos del request para asegurar consistencia
      $validated = $request->validate([
        'filtroFechaInicio' => 'required|date',
        'filtroFechaFin' => 'required|date|after_or_equal:filtroFechaInicio',
      ]);

      $where = 'CAST(dtFecha as DATE) >= CAST(' . "'" . $validated['filtroFechaInicio'] . "'" . ' as DATE) AND CAST(dtFecha as DATE) <= CAST(' . "'" . $validated['filtroFechaFin'] . "'" . ' as DATE)';

      // Usar `selDesdeTablaOVista` para realizar la consulta
      $query = $this->selDesdeTablaOVista(
        self::schema,
        'V_auditoria_accesos',
        '*',
        $where,
      );

      if ($query instanceof Collection) {
        $query = $query->sortByDesc('dtFecha')->values();
      }

      // Retornar la respuesta exitosa usando ResponseHandler
      return ResponseHandler::success($query, 'Auditoría de accesos obtenida correctamente.');
    } catch (Exception $e) {
      // Retornar un error con ResponseHandler
      return ResponseHandler::error('Error al obtener la auditoría de accesos.', 500, $e->getMessage());
    }
  }
}