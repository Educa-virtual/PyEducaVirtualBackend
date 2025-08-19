<?php

namespace App\Http\Controllers\ere;

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

use Exception;
use Hashids\Hashids;
use Illuminate\Http\Request;

class UgelesController extends ApiController
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function obtenerUgeles()
    {
        $campos = 'iUgelId,cUgelNombre';
        $where = '';
        $params = [
            'acad',
            'ugeles',
            $campos,
            $where
        ];
        try {
            $preguntas = DB::select('EXEC grl.sp_SEL_DesdeTabla_Where
                @nombreEsquema = ?,
                @nombreTabla = ?,
                @campos = ?,
                @condicionWhere = ?
            ', $params);

            return $this->successResponse(
                $preguntas,
                'Datos obtenidos correctamente'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Erro No!');
        }
    }

    public function obtenerUgelesIdCifrado()
    {
        $campos = 'iUgelId,cUgelNombre';
        $where = '';
        $params = [
            'acad',
            'ugeles',
            $campos,
            $where
        ];
        try {
            $ugeles = DB::select('EXEC grl.sp_SEL_DesdeTabla_Where
                @nombreEsquema = ?,
                @nombreTabla = ?,
                @campos = ?,
                @condicionWhere = ?
            ', $params);
            foreach ($ugeles as $ugel) {
                $ugel->iUgelId = $this->hashids->encode($ugel->iUgelId);
            }
            return $this->successResponse(
                $ugeles,
                'Datos obtenidos correctamente'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Erro No!');
        }
    }
}
