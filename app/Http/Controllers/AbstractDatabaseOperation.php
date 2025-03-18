<?php

namespace App\Http\Controllers;

use App\Contracts\DataReturnStrategy;
use App\Helpers\ResponseHandler;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Clase abstracta para centralizar operaciones con base de datos.
 */
abstract class AbstractDatabaseOperation
{
    /**
     * Valida que los datos tengan esquema y tabla.
     */
    protected function hasValidRequest(Request|array $request): bool
    {
        // Obtener los parámetros esperados (asegurar que sea un array)
        $params_expected = $this->getParams() ?? [];
    
        // Obtener los parámetros recibidos dependiendo del tipo de `$request`
        $params_received = $request instanceof Request
            ? array_keys($request->all() ?? [])  // Si es Request, obtener claves de los datos
            : array_keys($request ?? []);        // Si es array, obtener claves directamente
    
        // Asegurar que ambos son arrays y ordenarlos
        if (!is_array($params_expected) || !is_array($params_received)) {
            throw new Exception('Error en los parámetros.');
        }
    
        sort($params_received);
        sort($params_expected);
    
        return $params_received === $params_expected;
    }
    /**
     * Ejecuta una consulta con parámetros y un procedimiento almacenado.
     */
    protected function executeQuery(array $params, string $procedure)
    {

        $params = array_filter($params); // Filtrar valores nulos
        $placeholders = implode(',', array_fill(0, count($params), '?'));
        return collect(DB::select("EXEC $procedure $placeholders", $params));
    }

    /**
     * Método para procesar múltiples esquemas/tablas.
     */
    protected function processMultipleRequests(Request $request, array $queries, string $procedure)
    {
        $results = collect();

        $params = $this->getParams();

        foreach ($queries as $query) {


            
            if (!$this->hasValidRequest($query)) {
                throw new Exception('Error en la solicitud de los datos.');
            }

            $queryParams = array_values(Arr::only($query, $params));

            $result = $this->executeQuery($queryParams, $procedure);

            $results->push($result);
        }

        return $results;
    }

    /**
     * Método principal para manejar la solicitud.
     */
    public function handleRequest(Request $request, DataReturnStrategy $strategy): Collection|JsonResponse
    {
        try {

            $procedure = $this->getProcedureName();

            // Obtener parámetros de la solicitud
            $queryParams = $this->extractQueryParams($request);

            if ($this->hasValidRequest($request)) {



                $query = $this->executeQuery($queryParams, $procedure);

                return $strategy->handle($query);
            }

            // throw new Exception('Fallo al verificar.');

            // Manejo de solicitudes múltiples
            $queries = $request->all();
            $results = $this->processMultipleRequests($request, $queries, $procedure);
            // $results = $this->handleMultipleRequests($request, $procedure);
            return $strategy->handle($results);
        } catch (Exception $e) {
            throw new Exception("Error durante la operación.: $e");
        }
    }

    /**
     * Extrae los parámetros de la solicitud con base en los parámetros esperados.
     */
    private function extractQueryParams(Request $request): array
    {
        $params = $this->getParams();
        return array_values($request->only($params));
    }

    /**
     * Maneja solicitudes múltiples cuando la estructura de parámetros no es válida.
     */
    private function handleMultipleRequests(Request $request, string $procedure): Collection
    {
        $queries = $request->all();
        return $this->processMultipleRequests($request, $queries, $procedure);
    }

    /**
     * Devuelve el nombre del procedimiento almacenado.
     */
    abstract protected function getProcedureName(): string;

    abstract protected function getParams(): array;
}
