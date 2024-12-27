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

    /**
     * 
     * Recuperar datos de una tabla o vista desde un procedimiento almacenado.
     * 
     * Este método permite ejecutar un procedimiento almacenado para consultar 
     * datos desde una tabla o vista, aplicando filtros opcionales.
     * 
     * @param string $request->esquema Nombre del esquema de la base de datos.
     * @param string $request->tablaOVista Nombre de la tabla o vista a consultar.
     * @param string $request->campos Campos a seleccionar, separados por comas (por 
     * defecto '*').
     * @param string|null $request->condicionWhere Condición WHERE para filtrar los 
     * resultados (opcional).
     * @return Collection|ResponseHandler
     * 
     * @throws \Exception Si ocurre un error durante la ejecución de la consulta.
     * 
     * @example Uso básico:
     * ```php
     * $query = $this->selDesdeTablaOVista('grl', 'personas');
     * ```
     * 
     * @example Uso con campos específicos:
     * ```php
     * $query = $this->selDesdeTablaOVista('grl', 'personas', 'cPersPaterno, cPersMaterno, cPersNombre');
     * ```
     * 
     * @example Uso con filtro WHERE:
     * ```php
     * $query = $this->selDesdeTablaOVista('grl', 'personas', 'cPersPaterno, cPersMaterno, cPersNombre', 'iPersId = 1');
     * ```
     */

    public function selDesdeTablaOVista(Request $request, DataReturnStrategy $strategy): Collection|JsonResponse
    {

        if (isset($request->esquema) && isset($request->tabla)) {

            $params = [$request->esquema, $request->tabla, $request->campos];

            if (isset($request->where)) {
                $params[] = $request->where;
            }

            $placeholders = implode(',', array_fill(0, count($params), '?'));

            $formattedQuery = collect();

            try {
                $query = collect(DB::select("EXEC grl.SP_SEL_DesdeTablaOVista $placeholders", $params));

                return $strategy->handle($query);
            } catch (Exception $e) {
                return ResponseHandler::error('Error al obtener los datos.', 500, $e->getMessage());
            }
        }

        foreach ($request->all() as $query) {
            if (!isset($query['esquema']) or !isset($query['tabla'])) {
                return ResponseHandler::error('Error en la solicitud de los datos.');
            }
        }
        return response()->json('Es un array de objetos');




        if (is_array($request->data)) {
            $formattedQuery = collect(); // Usar Collection en lugar de un arreglo

            foreach ($request->data as $registro) {
                $params = [$request->esquema, $request->tabla];

                if (is_string($registro['campos'])) {
                    $params[] = $registro['campos'];
                }
                if (is_string($registro['where'])) {
                    $params[] = $registro['where'];
                }

                $placeholders = implode(',', array_fill(0, count($params), '?'));

                try {
                    // Ejecutar la consulta
                    $query = collect(DB::select("EXEC grl.SP_SEL_DesdeTablaOVista $placeholders", $params));

                    // Formatear los valores JSON dentro de cada elemento de la colección
                    $formattedQuery = $formattedQuery->merge($query->map(function ($item) {
                        $formattedItem = collect(); // Colección para el item actual

                        foreach ((array)$item as $key => $value) { // Castear a array para iterar
                            if (is_string($value) && json_decode($value) !== null) {
                                $formattedItem->put($key, json_decode($value, true));
                            } else {
                                $formattedItem->put($key, $value);
                            }
                        }

                        return $formattedItem;
                    }));
                } catch (Exception $e) {
                    return ResponseHandler::error('Error al obtener los datos.', 500, $e->getMessage());
                }
            }

            return $formattedQuery; // Retornar la colección
        } else {
            return ResponseHandler::error('Error al obtener los datos.');
        }
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
