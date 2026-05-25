<?php

namespace App\Http\Controllers\ere;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\ApiController;
use App\Models\acad\Curso;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class cursoController extends ApiController
{
    public function obtenerCursos(Request $request)
    {
        try {
            $data = Curso::selCursos($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
