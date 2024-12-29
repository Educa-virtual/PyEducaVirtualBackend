<?php

namespace App\Http\Controllers;

use App\Contracts\DataReturnStrategy;
use App\Helpers\ResponseHandler;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Clase abstracta para centralizar operaciones con base de datos.
 */
abstract class AbstractDatabaseOperation
{
    /**
     * Valida que los datos tengan esquema y tabla.
     */
    protected function hasValidSchemaAndTable(array|Request $data): bool
    {
        return isset($data['esquema']) && isset($data['tabla']);
    }

    /**
     * Ejecuta una consulta con parámetros y un procedimiento almacenado.
     */
    protected function executeQuery(array $params, string $procedure): Collection
    {
        $params = array_filter($params); // Filtrar valores nulos
        $placeholders = implode(',', array_fill(0, count($params), '?'));

        return collect(DB::select("EXEC $procedure $placeholders", $params));
    }

    /**
     * Método para procesar múltiples esquemas/tablas.
     */
    protected function processMultipleRequests(array $queries, string $procedure): Collection
    {
        $results = collect();

        foreach ($queries as $query) {
            if (!$this->hasValidSchemaAndTable($query)) {
                throw new Exception('Error en la solicitud de los datos.');
            }

            $result = $this->executeQuery([
                $query['esquema'],
                $query['tabla'],
                $query['campos'] ?? null,
                $query['where'] ?? null,
                $query['campoId'] ?? null,
                $query['valorId'] ?? null,
            ], $procedure);

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

            if ($this->hasValidSchemaAndTable($request)) {
                $query = $this->executeQuery([
                    $request->esquema,
                    $request->tabla,
                    $request->campos ?? null,
                    $request->where ?? null,
                    $request->campoId ?? null,
                    $request->valorId ?? null,
                ], $procedure);

                return $strategy->handle($query);
            }

            $queries = $request->all();
            $results = $this->processMultipleRequests($queries, $procedure);

            return $strategy->handle($results);
        } catch (Exception $e) {
            return ResponseHandler::error('Error durante la operación.', 500, $e->getMessage());
        }
    }

    /**
     * Devuelve el nombre del procedimiento almacenado.
     */
    abstract protected function getProcedureName(): string;
}
