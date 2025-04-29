<?php

namespace App\Models\acad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuzonSugerencia extends Model
{
    public static function registrarBuzon(Request $request, $usuario)
    {
        /*$request->validate([
            'cAsunto' => 'required|string|max:255',
            'cSugerencia' => 'required|string',
            'iPrioridadId' => 'required|integer|exists:acad.prioridades,iPrioridadId',
        ]);*/
        $request->validate([
            'cAsunto' => 'required|string|max:255',
            'cSugerencia' => 'required|string',
            'iPrioridadId' => 'required|integer'
        ]);

        $estudiante = DB::selectOne("SELECT iEstudianteId FROM acad.estudiantes WHERE iPersId=?", [$usuario->iPersId]);
        DB::statement("INSERT INTO [acad].[buzon_sugerencias]
            ([iEstudianteId]
            ,[cAsunto]
            ,[cSugerencia]
            ,[iPrioridadId]
            ,[dtFechaCreacion]) VALUES (?,?,?,?,GETDATE())", [$estudiante->iEstudianteId, $request->cAsunto, $request->cSugerencia, $request->iPrioridadId]);
    }
}
