<?php

namespace App\Models\acad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Bandeja extends Model
{
    public static function BandejaEntradaEstudiante($request)
    {
        $data = DB::select("EXEC [acad].[Sp_SEL_bandeja_estudiantes]
            @iEstudianteId=?
            ,@iYAcadId=?
            ,@iSedeId=?"
            , [
            $request->header('iEstudianteId'),
            $request->header('iYAcadId'),
            $request->header('iSedeId')
        ]);
        return $data;
    }

    public static function BandejaEntradaDocente(Request $request)
    {
        $data = DB::select("EXEC [acad].[Sp_SEL_bandeja_docente]
            @iDocenteId=?,
            @iSedeId=?,
            @iYAcadId=?", [
            $request->header('iEstudianteId'),
            $request->header('iSedeId'),
            $request->header('iYAcadId')
        ]);
        return 1;
    }
}
