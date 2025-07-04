<?php

namespace App\Services\enc;

use App\Helpers\VerifyHash;
use App\Http\Requests\enc\RegistrarCategoriaRequest;
use App\Models\enc\Categoria;
use App\Models\enc\Encuesta;
use Illuminate\Support\Facades\DB;

class EncuestasService
{
    public static function obtenerEncuestasPorCategoria($iCategoriaEncuestaId)
    {
        $id = VerifyHash::decodesxId($iCategoriaEncuestaId);
        $data = Encuesta::selEncuestasXCategoria($id);
        foreach ($data as $encuesta) {
            $encuesta->iConfEncId = VerifyHash::encodexId($encuesta->iConfEncId);
        }
        return $data;
    }

    public static function eliminarEncuesta($iConfEncId)
    {
        $id = VerifyHash::decodesxId($iConfEncId);
        DB::statement('UPDATE enc.configuracion_encuesta SET iEstado = 0 WHERE iConfEncId = ?', [$id]);
    }
}
