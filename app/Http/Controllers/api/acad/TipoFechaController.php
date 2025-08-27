<?php

namespace App\Http\Controllers\api\acad;

use App\Helpers\CollectionStrategy;
use App\Helpers\ResponseHandler;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoFechaController extends Controller
{
    const schema = "acad";

    public function getTiposFechas(Request $request)
    {
        try {
            $request->replace([
                'esquema' => self::schema,
                'tabla' => 'tipo_fechas',
                'campos' => '*',
                'where' => "iTipoFerId IN (4)",
            ]);

            $strategy = new CollectionStrategy();
            $apiController = new ApiController($strategy);
            $query = $apiController->getData($request);

            if ($query instanceof Collection) {
                $query = $query->sortByDesc('dtFeriado')->values();
            }

            return ResponseHandler::success($query, 'Feriados nacionales obtenidos correctamente.');
        } catch (\Exception $e) {
            return ResponseHandler::error(
                'Error al obtener los feriados nacionales.',
                500,
                $e->getMessage()
            );
        }
    }
}
