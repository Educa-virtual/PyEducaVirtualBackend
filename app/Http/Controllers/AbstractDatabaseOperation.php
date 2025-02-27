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
    protected function hasValidStructureParams(Request|array $request): bool
    {
        return $this->getParams() === $request->keys(); 
    }

    /**
     * Ejecuta una consulta con parámetros y un procedimiento almacenado.
     */
    protected function executeQuery(array $params, string $procedure): Collection
    {



        $params = array_filter($params); // Filtrar valores nulos
        $placeholders = implode(',', array_fill(0, count($params), '?'));

        $output = new ConsoleOutput();
        $output->writeln("<info>Procesando solicitud...</info>");

        
        return collect(DB::select("EXEC $procedure $placeholders", $params));

    }

    /**
     * Método para procesar múltiples esquemas/tablas.
     */
    protected function processMultipleRequests(Request $request, array $queries, string $procedure): Collection
    {
        $results = collect();

        $params = $this->getParams();

        foreach ($queries as $query) {
            if (!$this->hasValidStructureParams($query)) {
                throw new Exception('Error en la solicitud de los datos.');
            }

            $queryParams = array_values(Arr::only($query, $params));

            $result = $this->executeQuery($queryParams, $procedure);

            $results->push($result->first());
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
                
                $params = $this->getParams();
                
                $queryParams = array_values($request->only($params));
                

                if ($this->hasValidStructureParams($request)) {

                    $query = $this->executeQuery($queryParams, $procedure);

                    return $strategy->handle($query);
                }

                $queries = $request->all();
                $results = $this->processMultipleRequests($request, $queries, $procedure);

                return $strategy->handle($results);
            } catch (Exception $e) {
                return ResponseHandler::error('Error durante la operación.', 500, $e->getMessage());
            }
        }

    /**
     * Devuelve el nombre del procedimiento almacenado.
     */
    abstract protected function getProcedureName(): string;

    abstract protected function getParams(): array;
}
