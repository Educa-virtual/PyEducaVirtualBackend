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

    $queries = [];
    // Escuchar todas las consultas
    DB::listen(function ($query) use (&$queries) {
      $queries[] = [
        'sql' => $query->sql,
        'bindings' => $query->bindings,
        'time' => $query->time,
      ];
    });
    /**
     * @var \Illuminate\Http\JsonResponse $response
     */
    $response = $next($request);


    

    $originalContent = $response->getData(true);

    $originalContent['request'] = $queries;

    $response->setData($originalContent);

    return $response;
  }
}
