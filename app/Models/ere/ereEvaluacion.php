<?php

// namespace App\Models\ere;
namespace App\Models\Ere;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class ereEvaluacion extends Model
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
        return DB::select('EXEC ere.SP_INS_evaluaciones ?,?,?,?,?,?,?', $params);
    }
    public static function actualizarEvaluacion(array $params)
    {
        // Ejecutar el procedimiento almacenado para actualizar la evaluación
        //Se cambio el nombre sp_UPD_Evaluaciones
        $result = DB::select('EXEC ere.SP_UPD_evaluaciones ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $params);
        // Si el procedimiento devuelve resultados, asumimos que el primer resultado es la evaluación actualizada
        return !empty($result) ? $result[0] : null;
    }
}
