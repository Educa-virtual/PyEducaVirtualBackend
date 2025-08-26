<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MantenimientoIe extends Model
{
    protected $table = 'acad.institucion_educativas';
    protected $primaryKey = 'iIieeId';
    public $timestamps = false;

    protected $fillable = [
        'cIieeCodigoModular',
        'iDsttId',
        'iZonaId',
        'iTipoSectorId',
        'cIieeNombre',
        'cIieeRUC',
        'cIieeDireccion',
        'cIieeLogo',
        'iEstado',
        'iNivelTipoId',
        'iUgelId',
        'iSesionId',
        'dtCreado',
        'dtActualizado'
    ];

    protected $casts = [
        'dtCreado' => 'datetime',
        'dtActualizado' => 'datetime',
        'iEstado' => 'integer'
    ];

    public function scopeActivos($query)
    {
        return $query->where('iEstado', 1);
    }

    public static function parse($errorMessage)
    {
        if (str_contains($errorMessage, 'Violation of UNIQUE KEY constraint')) {
            return 'Ya existe un registro con estos datos únicos';
        }
        
        if (str_contains($errorMessage, 'Cannot insert duplicate key')) {
            return 'No se puede insertar: clave duplicada';
        }
        
        if (str_contains($errorMessage, 'Foreign key constraint')) {
            return 'Error de referencia: verifique los datos relacionados';
        }
        
        if (str_contains($errorMessage, 'Invalid column name')) {
            return 'Error en la estructura de datos';
        }
        
        return 'Error en el procesamiento de datos: ' . $errorMessage;
    }
}
