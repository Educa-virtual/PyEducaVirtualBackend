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
    {
        return DB::select('EXEC ere.sp_SEL_Evaluaciones');
    }

    public static function guardarEvaluaciones($params)
    {
        return DB::select('EXEC ere.sp_INS_Evaluaciones ?,?,?,?,?,?,?,?,?,?,?,?,?',$params);
    }
    //CON ESTE CODIGO SE USA DIRECTO A LA TABLA: TABLE
    //  protected $table = 'ere.evaluacion'; // Nombre de la tabla

    // // Si la tabla tiene una clave primaria diferente a 'id', defínela aquí
    // protected $primaryKey = 'iEvaluacionId';
}
