<?php

// namespace App\Models\ere;
namespace App\Models\ere;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Evaluacion extends Model
{
    // use HasFactory;

    // //protected $table = 'evaluacion'; // Nombre de la tabla
    // protected $table = 'evaluacion';
    // //protected $primaryKey = 'ere.sp_SEL_iEvaluacionId'; // Clave primaria

    // // Si las columnas no siguen la convención de pluralización de Laravel, se especifican aquí
    // protected $fillable = [
    //     'iEvaluacionId',
    //     'idTipoEvalId',
    //     'iNivelEvalId',
    //     'dtEvaluacionCreacion',
    //     'cEvaluacionNombre',
    //     'cEvaluacionDescripcion',
    //     'cEvaluacionUrlDrive',
    //     'cEvaluacionUrlPlantilla',
    //     'cEvaluacionUrlManual',
    //     'cEvaluacionUrlMatriz',
    //     'cEvaluacionObs',
    //     'dtEvaluacionLiberarMatriz',
    //     'dtEvaluacionLiberarCuadernillo',
    //     'dtEvaluacionLiberarResultados',
    // ];

    // // public static function obtenerTodas()
    // // {
    // //     return self::all();
    // // }


    //ESTA PARTE DEL CODIGO USA PROCEDIMIENTO ALMACENADO: PROCEDIMIENTO

    protected $table = 'evaluacion'; // Nombre de la tabla

    // Si la tabla tiene una clave primaria diferente a 'id', defínela aquí
    protected $primaryKey = 'iEvaluacionId';

    // Deshabilitar las marcas de tiempo si no se usan
    public $timestamps = false;

    // Aquí puedes definir un método para ejecutar el procedimiento almacenado
    public static function obtenerEvaluaciones()
    { //Se cambio el nombre sp_SEL_Evaluaciones
        return DB::select('EXEC ere.SP_SEL_evaluaciones');
    }

    public static function guardarEvaluaciones($params)
    {
        return DB::select('EXEC ere.SP_INS_evaluaciones ?,?,?,?,?,?,?,?', $params);
    }
    public static function actualizarEvaluacion(array $params)
    {
        // Ejecutar el procedimiento almacenado para actualizar la evaluación
        //Se cambio el nombre sp_UPD_Evaluaciones
        $result = DB::select('EXEC ere.SP_UPD_evaluaciones ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $params);
        // Si el procedimiento devuelve resultados, asumimos que el primer resultado es la evaluación actualizada
        return !empty($result) ? $result[0] : null;
    }

    public static function selCantidadMaxPreguntas($iEvaluacionId, $iCursosNivelGradId)
    {
        $cantidad = DB::selectOne("SELECT TOP 1 iExamenCantidadPreguntas AS cantidad FROM ere.examen_cursos WHERE iEvaluacionId=?
	        AND iCursoNivelGradId=?", [$iEvaluacionId, $iCursosNivelGradId]);
        return $cantidad->cantidad;
    }

    public static function selEvaluacionesEstudiantePorAnio($iEstudianteId, $anio)
    {
        return DB::select("EXEC ere.Sp_SEL_EvaluacionesEstudiantePorAnio @iEstudianteId=?, @anioEvaluacion=?", [$iEstudianteId, $anio]);
    }

    public static function selResultadoEvaluacionEstudiante($iEstudianteId, $iEvaluacionId)
    {
        return DB::select("EXEC [ere].[Sp_SEL_resultadoEvaluacionEstudiante] @iEstudianteId=?, @iEvaluacionId=?", [$iEstudianteId, $iEvaluacionId]);
    }

    public static function selEvaluacionPorArea($iEvaluacionId, $iCursoNivelGradId)
    {
        return DB::selectOne("SELECT TOP 1 e.cEvaluacionNombre,ne.cNivelEvalNombre,c.cCursoNombre,g.cGradoAbreviacion,
		nt.cNivelTipoNombre,nt.iNivelTipoId
FROM ere.evaluacion AS e
INNER JOIN ere.nivel_evaluaciones AS ne ON e.iNivelEvalId = ne.iNivelEvalId
INNER JOIN ere.examen_cursos AS ec ON ec.iEvaluacionId = e.iEvaluacionId
INNER JOIN acad.cursos_niveles_grados AS cng ON cng.iCursosNivelGradId = ec.iCursoNivelGradId
INNER JOIN acad.cursos AS c ON c.iCursoId = cng.iCursoId
INNER JOIN acad.nivel_grados AS ng ON ng.iNivelGradoId = cng.iNivelGradoId
INNER JOIN acad.grados AS g ON g.iGradoId = ng.iGradoId
INNER JOIN acad.nivel_ciclos AS nic ON nic.iNivelCicloId = ng.iNivelCicloId
INNER JOIN acad.nivel_tipos AS nt ON nt.iNivelTipoId = nic.iNivelTipoId
WHERE e.iEvaluacionId=? AND cng.iCursosNivelGradId=?", [$iEvaluacionId, $iCursoNivelGradId]);
    }

    public static function selPreguntasPorEvaluacionArea($iEvaluacionId, $iCursoNivelGradId) {
        return DB::select("SELECT p.iPreguntaId, p.cPregunta,p.cPreguntaTextoAyuda,p.iEncabPregId
,(SELECT al.iAlternativaId, al.cAlternativaDescripcion, al.cAlternativaLetra, al.cAlternativaImagen, p.iPreguntaId, 0 AS iMarcado
FROM ere.alternativas AS al
WHERE al.iPreguntaId = p.iPreguntaId AND al.iEstado = 1
FOR JSON PATH) AS alternativas, ecanbp.cEncabPregContenido
FROM ere.evaluacion_preguntas AS ep
INNER JOIN ere.preguntas AS p ON p.iPreguntaId = ep.iPreguntaId
LEFT JOIN  ere.encabezado_preguntas AS ecanbp ON ecanbp.iEncabPregId = p.iEncabPregId
WHERE ep.iEvaluacionId = ? AND p.bPreguntaEstado = 1 AND p.iCursosNivelGradId = ?", [$iEvaluacionId, $iCursoNivelGradId]);
    }
}
