<?php

namespace App\Http\Controllers\grl;

use App\Http\Controllers\Controller;
use App\Models\grl\Prioridad;
use Illuminate\Http\Request;

class PrioridadController extends Controller
{
    public function index(Request $request)
    {
        $prioridades = Prioridad::obtenerListaPrioridades();
        return response()->json($prioridades);

    }
}
