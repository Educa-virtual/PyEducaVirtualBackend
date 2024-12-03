<?php

namespace App\Http\Controllers\api\acad;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GestionInstitucionalController extends Controller
{
    
    // no tocar
    public function listarPersonalIes(Request $request)
    {
        $solicitud = [
        $request->iSedeId,
        $request->iYAcadId
        ];

        $query = DB::select("EXEC acad.SP_SEL_listarPersonalIesXiSedeXiYAcadId ?,?", //actualizado
        $solicitud);

        try {
        $response = [
            'validated' => true,
            'message' => 'se obtuvo la información',
            'data' => $query,
        ];

        $estado = 201;
        } catch (Exception $e) {
        $response = [
            'validated' => false,
            'message' => $e->getMessage(),
            'data' => [],
        ];
        $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }

    public function insertMaestroDetalle(Request $request)
    {
        $solicitud = [

            $request->esquema,       //-- Esquema de la tabla maestra
            $request->tablaMaestra, //NVARCHAR(128),   -- Nombre de la tabla maestra
            $request->datosJSONMaestro, // NVARCHAR(MAX), -- Datos en formato JSON para la tabla maestra
            $request->tablaDetalle, // NVARCHAR(128),   -- Nombre de la tabla detalle
            $request->datosJSONDetalles, // NVARCHAR(MAX), -- Datos en formato JSON (array) para los detalles
            $request->campoFK // NVARCHAR(128)
    
        ];

        $query = DB::select("EXEC grl.SP_INS_EnTablaMaestroDetalleDesdeJSON ?,?,?,?,?,?", //actualizado
        $solicitud);

        try {
        $response = [
            'validated' => true,
            'message' => 'se obtuvo la información',
            'data' => $query,
        ];

        $estado = 201;
        } catch (Exception $e) {
        $response = [
            'validated' => false,
            'message' => $e->getMessage(),
            'data' => [],
        ];
        $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }

    public function insertMaestro(Request $request)
    {
        $solicitud = [
            $request->esquema,    //NVARCHAR(128),   -- Esquema de la tabla
            $request->tabla,      //NVARCHAR(128),    -- Nombre de la tabla
            $request->datosJSON,  //NVARCHAR(MAX) -- Datos en formato JSON
        ];

        $query = DB::select("EXEC grl.SP_INS_EnTablaDesdeJSON ?,?,?", //actualizado
        $solicitud);

        try {
        $response = [
            'validated' => true,
            'message' => 'se obtuvo la información',
            'data' => $query,
        ];

        $estado = 201;
        } catch (Exception $e) {
        $response = [
            'validated' => false,
            'message' => $e->getMessage(),
            'data' => [],
        ];
        $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }

}



