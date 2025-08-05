<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogroAlcanzado extends Model
{
    protected $table = 'acad.competencias_cursos';
    protected $primaryKey = 'iCompCursoId';
    public $timestamps = false;
    
    protected $fillable = [
        'iCursoId',
        'iCompetenciaId',
        'iNivelTipoId',
        'iEstado'
    ];
}
