<?php

namespace App\Models\acad;

use Illuminate\Database\Eloquent\Model;
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
            $request->iEstudianteId,
            $request->iYAcadId, 
            $request->iSedeId, 
        ]);

        foreach($data as $valor){
            if (isset($valor->cProgActDescripcion)) {
                $texto = strip_tags($valor->cProgActDescripcion);
                $texto = html_entity_decode($texto, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $texto = str_replace("\xc2\xa0", ' ', $texto);
                $texto = trim($texto);
                $valor->cProgActDescripcion = $texto;
            }
        }

        return $data;
    }

    public static function BandejaEntradaDocente($request)
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
