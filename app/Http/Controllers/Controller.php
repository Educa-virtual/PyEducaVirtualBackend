<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;


abstract class Controller extends BaseController
{
    //
    public function response($query)
    {
        $response = [
            'validated' => true,
            'message' => '',
            'data' => [],
        ];
        $estado = 200;

        try {
            $response['message'] = 'Se obtuvo la información';
            $response['data'] = $query;
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }


    public function selDesdeTablaOVista($esquema, $tablaOVista, $datosJSON = null, $condicionWhere = null)
    {
        $params = [$esquema, $tablaOVista];

        if (!is_null($datosJSON)) {
            $params[] = $datosJSON;
        }
    
        if (!is_null($condicionWhere)) {
            $params[] = $condicionWhere;
        }
    
        // Construir los placeholders dinámicos
        $placeholders = implode(',', array_fill(0, count($params), '?'));

        $query = collect(DB::select("EXEC grl.SP_SEL_DesdeTablaOVista $placeholders", $params));

        return $query;
    }
}
