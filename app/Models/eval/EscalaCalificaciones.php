<?php

namespace App\Models\eval;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EscalaCalificaciones extends Model
{
    public static function selEscalaCalificaciones($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iTipoEscalaId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC eval.Sp_SEL_escalaCalificaciones $placeholders", $parametros);
    }

    public static function insEscalaCalificaciones($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iTipoEscalaId ?? NULL,
            $request->cEscalaCalifNombre ?? NULL,
            $request->cEscalaCalifDescripcion ?? NULL,
            $request->cEscalaCalifLetra ?? NULL,
            $request->nEscalaCalifEquivalente ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC eval.Sp_INS_escalaCalificaciones $placeholders", $parametros);
    }

    public static function updEscalaCalificaciones($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iEscalaCalifId ?? NULL,
            $request->cEscalaCalifNombre ?? NULL,
            $request->cEscalaCalifDescripcion ?? NULL,
            $request->cEscalaCalifLetra ?? NULL,
            $request->nEscalaCalifEquivalente ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC eval.Sp_UPD_escalaCalificaciones $placeholders", $parametros);
    }
}
