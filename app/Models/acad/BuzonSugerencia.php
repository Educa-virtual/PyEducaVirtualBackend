<?php

namespace App\Models\acad;

use App\Http\Requests\acad\EliminarSugerenciaRequest;
use App\Http\Requests\acad\RegistrarSugerenciaRequest;
use App\Http\Requests\GeneralFormRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuzonSugerencia extends Model
{
    public static function insBuzonSugerencias(RegistrarSugerenciaRequest $request)
    {
        $request->merge(['iCredEntPerfId' => $request->header('iCredEntPerfId')]);
        $data = DB::selectOne("EXEC [acad].[SP_INS_buzonSugerencias]  ?,?,?,?", [
            $request->iCredEntPerfId,
            $request->cAsunto,
            $request->cSugerencia,
            $request->iPrioridadId
        ]);
        return $data->iSugerenciaId;
    }

    public static function selBuzonSugerencias(Request $request)
    {
        $data = DB::select("EXEC [acad].[SP_SEL_buzonSugerenciasAlumno] @iCredEntPerfId=?", [
            $request->header('iCredEntPerfId')
        ]);
        return $data;
    }

    public static function delBuzonSugerencias($iSugerenciaId, Request $request)
    {
        $data = DB::statement("EXEC [acad].[SP_DEL_buzonSugerenciasAlumno] @iCredEntPerfId=?, @iSugerenciaId=?", [
            $request->header('iCredEntPerfId'),
            $iSugerenciaId,
        ]);
        return $data;
    }
}
