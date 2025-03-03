<?php

namespace App\Http\Controllers\seg;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DatabaseController extends Controller
{
    public function store(Request $request) {

        return response()->json([
            'status' => 'Success',
            'message' => 'Se ha creado el backup correctamente'
        ], Response::HTTP_OK);
    }
}
