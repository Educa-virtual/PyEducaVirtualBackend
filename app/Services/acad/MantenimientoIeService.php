<?php

namespace App\Services\Acad;

use Illuminate\Support\Facades\DB;
use Exception;

class MantenimientoIeService
{
    /**
     * Listar instituciones educativas 
     */
    public function listarInstituciones(array $parametros): array
    {
        return DB::select('EXEC acad.SP_MantenimientoIE_Listar ?,?,?,?,?,?,?,?,?,?', $parametros);
    }

    /**
     * Crear nueva institución educativa
     */
    public function crear(array $parametros): array
    {
        return DB::select('EXEC acad.SP_MantenimientoIE_Crear ?,?,?,?,?,?,?,?,?,?,?', $parametros);
    }

    /**
     * Actualizar institución educativa
     */
    public function actualizar(array $parametros): array
    {
        return DB::select('EXEC acad.SP_MantenimientoIE_Actualizar ?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
    }

    /**
     * Eliminar institución educativa (lógico)
     */
    public function eliminar(array $parametros): array
    {
        return DB::select('EXEC acad.SP_MantenimientoIE_Eliminar ?,?', $parametros);
    }
}
