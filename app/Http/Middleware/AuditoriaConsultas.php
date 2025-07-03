<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AuditoriaConsultas
{
  /**
   * Middleware para gestionar procesos de auditoría en la base de datos
   * 
   * Este middleware inicia, detiene y registra auditorías basadas en una
   * cabecera específica (`iCredId`) presente en la solicitud HTTP
   * 
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {

    // Si la cabecera iCredId está presente, inicia la auditoría
    if ($request->header('iCredId')) {
      $query = DB::select('EXEC seg.SP_UPD_IniciarAuditoriaBackend');
    }

    /**
     * @var \Illuminate\Http\JsonResponse $response
     */

    $response = $next($request);

    // Si la cabecera iCredId está presente, detiene la auditoría y adjunta el iCredId a las consultas procesadas
    if ($request->header('iCredId')) {
      DB::statement('EXEC seg.SP_UPD_DetenerAuditoriaBackend');

      DB::select('EXEC grl.SP_UPD_EnTablaConJSON ?,?,?,?', [
        'seg',
        'auditorias_middleware',
        json_encode([
          'iCredId' => $request->header('iCredId')
        ]),
        json_encode([
          'COLUMN_NAME' => 'proceso_id',
          'VALUE' => $query[0]->proceso_id,
        ])
      ]);

    }

    return $response;
  }
}
