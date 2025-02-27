<?php

namespace App\Services\ere;

class AreasService
{
    public static function tieneArchivoPdfSubido($iEvaluacionid, $iCursosNivelGradId) {
        return file_exists(public_path("ere/evaluaciones/$iEvaluacionid/areas/$iCursosNivelGradId/examen.pdf"));
    }
}
