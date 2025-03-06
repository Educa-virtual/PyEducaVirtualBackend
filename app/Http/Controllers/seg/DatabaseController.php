<?php

namespace App\Http\Controllers\seg;

use App\Http\Controllers\Controller;
use App\Repositories\seg\DatabaseRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class DatabaseController extends Controller
{
    public function store(Request $request)
    {
        $base = 'EducaVirtual_DEV_180924';
        $ruta = 'D:\Programas\Microsoft SQL Server\MSSQL16.MSSQLSERVER\MSSQL\Backup';
        try {
            DatabaseRepository::backupDatabase($base, $ruta);
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
}
