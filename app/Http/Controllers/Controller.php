<?php

namespace App\Http\Controllers;

use Exception;
use App\Helpers\ResponseHandler;
use Illuminate\Http\JsonResponse;
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
     * @param string $esquema Nombre del esquema de la base de datos.
     * @param string $tablaOVista Nombre de la tabla o vista a consultar.
     * @param string $campos Campos a seleccionar, separados por comas (por 
     * defecto '*').
     * @param string|null $condicionWhere Condición WHERE para filtrar los 
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

    public function selDesdeTablaOVista($esquema, $tablaOVista, $campos = '*', $condicionWhere = null):Collection|JsonResponse
    {
        $params = [$esquema, $tablaOVista, $campos];

        if (!is_null($condicionWhere)) {
            $params[] = $condicionWhere;
        }

        // Construir los placeholders dinámicos
        $placeholders = implode(',', array_fill(0, count($params), '?'));

        try {
            // Retorna directamente los datos como colección
            return collect(DB::select("EXEC grl.SP_SEL_DesdeTablaOVista $placeholders", $params));
        } catch (Exception $e) {
            return ResponseHandler::error('Error al obtener los datos.', 500, $e->getMessage());
        }
    }
}
