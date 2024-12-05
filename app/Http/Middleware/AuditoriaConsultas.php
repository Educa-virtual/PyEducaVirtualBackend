<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuditoriaConsultas
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {

    if ($request->header('iCredId')) {
      # code...
      $query = DB::select('EXEC seg.SP_UPD_IniciarAuditoriaBackend');
    }


    /**
     * @var \Illuminate\Http\JsonResponse $response
     */
    $response = $next($request);

    if ($request->header('iCredId')) {
      # code...
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
  
      // $originalContent['request'] = $queries;
    }

    return $response;
  }
}
