<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoActividadController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = DB::table('aula.actividad_tipos')->get();
            return  $this->successResponse($data, 'Datos obtenidos correctamente');
        } catch (Exception $e) {
            $message = $this->handleAndLogError($e,  'Error al obtener los datos');
            return $this->errorResponse(null, $message);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
