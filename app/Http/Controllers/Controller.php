<?php

namespace App\Http\Controllers;

use App\Contracts\DataReturnStrategy;
use App\Helpers\JsonResponseStrategy;
use Exception;
use App\Helpers\ResponseHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;

abstract class Controller extends BaseController
{
    protected $model;

    public function selDesdeTablaOVista(Request $request, DataReturnStrategy $strategy): Collection|JsonResponse
    {
        try {
            // Verificar si la solicitud es para un único esquema/tabla
            if ($this->hasValidSchemaAndTable($request)) {
                $query = $this->executeQuery([
                    $request->esquema,
                    $request->tabla,
                    $request->campos,
                    $request->where ?? null
                ]);

                return $strategy->handle($query);
            }

            // Verificar todas las entradas en el caso de múltiples esquemas/tablas
            $queries = $request->all();

            foreach ($queries as $query) {
                if (!$this->hasValidSchemaAndTable($query)) {
                    return ResponseHandler::error('Error en la solicitud de los datos.');
                }
            }

            $formattedQueries = collect();

            foreach ($queries as $query) {
                $result = $this->executeQuery([
                    $query['esquema'],
                    $query['tabla'],
                    $query['campos'],
                    $query['where'] ?? null
                ]);

                $formattedQueries->push($result->first());
            }

            return $strategy->handle($formattedQueries);
        } catch (Exception $e) {
            return ResponseHandler::error('Error al obtener los datos.', 500, $e->getMessage());
        }
    }

    /**
     * Verifica si la solicitud tiene esquema y tabla válidos.
     */
    private function hasValidSchemaAndTable(array|Request $data): bool
    {
        return isset($data['esquema']) && isset($data['tabla']);
    }

    /**
     * Ejecuta la consulta con los parámetros proporcionados.
     */
    private function executeQuery(array $params): Collection
    {
        $params = array_filter($params); // Filtrar valores nulos
        $placeholders = implode(',', array_fill(0, count($params), '?'));

        return collect(DB::select("EXEC grl.SP_SEL_DesdeTablaOVista $placeholders", $params));
    }




    public function insEnTablaDesdeJSON(Request $request): Collection|JsonResponse
    {
        if (is_null($request->data)) {
            return ResponseHandler::error('Error sin datos que insertar.', 500);
        }

        $insertsIds = [];

        if (is_array($request->data)) {

            foreach ($request->data as $campos) {
                $params = [$request->esquema, $request->tabla];

                $params[] = json_encode($campos);

                $placeholders = implode(',', array_fill(0, count($params), '?'));

                try {
                    $insertIds[] = DB::select("EXEC grl.SP_INS_EnTablaDesdeJSON $placeholders", $params);
                } catch (Exception $e) {
                    return ResponseHandler::error('Error al insertar los datos.', 500, $e->getMessage());
                }
            }

            return collect($insertIds);
        } else {
            return ResponseHandler::error('Error al insertar los datos.');
        }
    }

    public function updEnTablaConJSON(Request $request): Collection|JsonResponse
    {
        if (is_null($request->data)) {
            return ResponseHandler::error('Error campos no especificados', 500);
        }

        if (is_array($request->data)) {

            foreach ($request->data as $registro) {

                foreach ($registro as $key => $value) {

                    $params = [$request->esquema, $request->tabla];

                    if (!is_null($registro['campos'])) {
                        $params[] = json_encode($registro['campos']);
                    }
                    if (!is_null($registro['where'])) {
                        $params[] = json_encode($registro['where']);
                    }

                    $placeholders = implode(',', array_fill(0, count($params), '?'));

                    try {
                        $updateIds[] = DB::select("EXEC grl.SP_UPD_EnTablaConJSON $placeholders", $params);
                    } catch (Exception $e) {
                        return ResponseHandler::error('Error al actualizar los datos.', 500, $e->getMessage());
                    }
                }
            }

            return collect($updateIds);
        } else {
            return ResponseHandler::error('Error al actualizar los datos.');
        }
    }
    public function delRegistroConTransaccion(Request $request): Collection|JsonResponse
    {

        if (is_null($request->campoId)) {
            return ResponseHandler::error('Error campos no especificados', 500);
        }

        if (is_array($request->data)) {

            foreach ($request->data as $registro) {

                foreach ($registro as $key => $value) {

                    $params = [$request->esquema, $request->tabla, $request->campoId];

                    if (isset($registro['valorId']) and !is_null($registro['valorId'])) {
                        $params[] = $registro['valorId'];
                    }

                    if (isset($registro['tablaHija']) and !is_null($registro['tablaHija']) and $registro['tablaHija'] != NULL) {
                        $params[] = $registro['tablaHija'];
                    }

                    $placeholders = implode(',', array_fill(0, count($params), '?'));

                    try {
                        $updateIds[] = DB::select("EXEC grl.SP_DEL_RegistroConTransaccion $placeholders", $params);
                    } catch (Exception $e) {
                        return ResponseHandler::error('Error al borrar los datos.', 500, $e->getMessage());
                    }
                }
            }

            return collect($updateIds);
        } else {
            return ResponseHandler::error('Error al borrar los datos.');
        }
    }
}
