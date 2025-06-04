<?php

namespace App\Http\Controllers\seg;

use App\Http\Controllers\Controller;
use App\Repositories\seg\DatabaseRepository;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class DatabaseController extends Controller
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function store(Request $request)
    {
        $iPersIdDescifrado = $this->hashids->decode($request->iPersId);
        if (empty($iPersIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }
        $base = env('DB_DATABASE');
        $ruta = env('DB_CARPETA_BACKUP');

        try {
            DatabaseRepository::backupDatabase($base, $ruta, $iPersIdDescifrado[0]);
            return response()->json([
                'status' => 'Success',
                'message' => 'Se ha creado el backup correctamente'
            ], Response::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json([
                'status' => 'Error',
                'message' => $ex->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function index()
    {
        $resultado = DatabaseRepository::obtenerHistorialBackups();

        return response()->json([
            'status' => 'Success',
            'message' => 'Se han obtenido los datos de backup correctamente',
            'data' => $resultado
        ], Response::HTTP_OK);
    }
}
