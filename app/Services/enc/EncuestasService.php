<?php

namespace App\Services\enc;

use App\Http\Requests\enc\RegistrarCategoriaRequest;
use App\Models\enc\Categoria;
use App\Models\enc\Encuesta;

class EncuestasService
{
    public static function obtenerEncuestasPorCategoria($iCategoriaEncuestaId)
    {
        return Encuesta::selEncuestasXCategoria($iCategoriaEncuestaId);
    }
}
