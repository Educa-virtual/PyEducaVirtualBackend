<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Helpers\ResponseHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Contracts\DataReturnStrategy;
use Illuminate\Support\Facades\Log;
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
        $params_expected = $this->getParamsRequest() ?? [];
    
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
    protected function executeQuery()
    {
        $procedure = $this->getProcedureName();


        $params = array_filter($this->getParamsProcedure()); // Filtrar valores nulos
        
        $placeholders = implode(',', array_fill(0, count($params), '?'));

        $msg = new ConsoleOutput();
        
        $text = "EXEC $procedure $placeholders " . implode(", ", $params);

        $msg->writeln("EXEC $procedure $placeholders " . implode(", ", $params));

        Log::info($text);

        return collect(DB::select("EXEC $procedure $placeholders", $params));
    }

    /**
     * Método para procesar múltiples esquemas/tablas.
     */
    protected function processMultipleRequests(Request $request, array $queries)
    {
        $results = collect();

        $params = $this->getParamsRequest();

        foreach ($queries as $query) {

            if (!$this->hasValidRequest($query)) {
                throw new Exception('Error en la solicitud de los datos.');
            }

            $result = $this->executeQuery();

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



            if ($this->hasValidRequest($request)) {

                $query = $this->executeQuery();

                return $strategy->handle($query);
            }

            // throw new Exception('Fallo al verificar.');

            // Manejo de solicitudes múltiples
            $queries = $request->all();
            $results = $this->processMultipleRequests($request, $queries);
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
        $params = $this->getParamsRequest();
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

    abstract protected function getParamsRequest(): array;

    abstract protected function getParamsProcedure(): array;

    abstract protected function getRequest(): Request;
}
