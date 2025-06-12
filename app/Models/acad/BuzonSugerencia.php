<?php

namespace App\Models\acad;

use App\Http\Requests\acad\EliminarSugerenciaRequest;
use App\Http\Requests\acad\RegistrarSugerenciaRequest;
use App\Http\Requests\GeneralFormRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    public static function selBuzonSugerenciasEstudiante(Request $request)
    {
        $data = DB::select("EXEC [acad].[SP_SEL_buzonSugerenciasEstudiante] @iCredEntPerfId=?", [
            $request->header('iCredEntPerfId')
        ]);
        return $data;
    }

    public static function delBuzonSugerencias($iSugerenciaId, Request $request)
    {
        $data = DB::statement("EXEC [acad].[SP_DEL_buzonSugerenciasEstudiante] @iCredEntPerfId=?, @iSugerenciaId=?", [
            $request->header('iCredEntPerfId'),
            $iSugerenciaId,
        ]);
        return $data;
    }

    public static function obtenerArchivosSugerencia($iSugerenciaId) {
        $rutaCarpeta = "sugerencias/$iSugerenciaId/";
        if (!Storage::disk('public')->exists($rutaCarpeta)) {
            return []; // Retorna un array vacío si la carpeta no existe
        }

        $archivos = Storage::disk('public')->files($rutaCarpeta);
        $listaArchivos = [];

        foreach ($archivos as $archivo) {
            $listaArchivos[] = [
                'nombreArchivo' => basename($archivo),
                'rutaCompleta' => $archivo,
            ];
        }

        return $listaArchivos;
    }
}
