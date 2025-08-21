<?php

namespace App\Services\enc;

use App\Helpers\VerifyHash;
use App\Http\Requests\enc\RegistrarConfiguracionEncuestaRequest;
use App\Models\enc\ConfiguracionEncuesta;
use App\Models\User;

class ConfiguracionEncuestasService
{
    public static function registrarConfiguracion(RegistrarConfiguracionEncuestaRequest $request, User $usuario)
    {
        $categoriaId = VerifyHash::decodesxId($request->iCategoriaEncuestaId);
        if ($request->iConfEncId == 0) {
            $iConfEncId = ConfiguracionEncuesta::insConfiguracionEncuesta($params = [
                $request->cConfEncNombre,
                $request->cConfEncSubNombre,
                $request->cConfEncDesc,
                $request->dConfEncInicio,
                $request->dConfEncFin,
                $request->iTiemDurId,
                $categoriaId,
                $usuario->iCredId,
                $request->iYAcadId,
                $request->bDirigidoDirectores,
                $request->bDirigidoDocentes,
                $request->bDirigidoEstudiantes,
                $request->bDirigidoApoderados,
                $request->bDirigidoEspDremo,
                $request->bDirigidoEspUgel
            ]);
            return $iConfEncId;
        } else {
        }
    }
}
