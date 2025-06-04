<?php

namespace App\Repositories\ere;

use Illuminate\Support\Facades\DB;

class NivelLogrosRepository
{
    public static function obtenerNivelLogros()
    {
        return DB::select('SELECT * FROM ere.nivel_logros ORDER BY iNivelLogroId');
    }

    public static function registrarNivelLogroPorCurso($nivelesRegistrar, $iCursosNivelGradId, $evaluacionId)
    {
        foreach ($nivelesRegistrar as $fila) {
            DB::statement('INSERT INTO [ere].[nivel_logro_curso]
                ([iNivelLogroId]
                ,[nNivelLCDesde]
                ,[nNivelLCHasta]
                ,[iCursosNivelGradId]
                ,[iEvaluacionId])
                VALUES (?,?,?,?,?)', [$fila['iNivelLogroId'], $fila['iDesde'], $fila['iHasta'], $iCursosNivelGradId, $evaluacionId]);
        }
    }

    public static function eliminarNivelLogroPorCurso($iCursosNivelGradId, $evaluacionId)
    {
        DB::statement('DELETE FROM [ere].[nivel_logro_curso] WHERE iCursosNivelGradId = ? AND iEvaluacionId = ?', [$iCursosNivelGradId, $evaluacionId]);
    }

    public static function obtenerNivelLogrosPorCurso($iCursosNivelGradId, $evaluacionId)
    {
        return DB::select('SELECT [iNivelLogroId],[nNivelLCDesde],[nNivelLCHasta] FROM [ere].[nivel_logro_curso]
            WHERE iCursosNivelGradId = ? AND iEvaluacionId = ?
            ORDER BY iNivelLogroId', [$iCursosNivelGradId, $evaluacionId]);
    }
}
