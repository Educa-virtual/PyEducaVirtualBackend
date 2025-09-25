<?php

namespace App\Services\hor;

use App\Models\hor\Horario;
use Illuminate\Http\Request;

class HorariosService
{
    public static function obtenerHorario($matricula) {
        return Horario::selHorario([$matricula->iYAcadId, $matricula->iSedeId, $matricula->iNivelGradoId, $matricula->iSeccionId]);
    }
}
