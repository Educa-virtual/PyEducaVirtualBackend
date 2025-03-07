<?php

namespace App\Http\Controllers\com;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GruposController extends Controller
{
    private $hashids;
    public function __construct()
    {
        $this->hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
    }

    private function decodeValue($value)
    {
        if (is_null($value)) {
            return null;
        }
        return is_numeric($value) ? $value : ($this->hashids->decode($value)[0] ?? null);
    }
    public function guardarMiembros(Request $request){
        $iPersId = $this->decodeValue($request->iPersId);
        $miembros = $request->miembros;
        $cGrupoNombre = $request->cGrupoNombre;
        $cGrupoDescripcion = $request->cGrupoDescripcion;
        
        $decodes = json_decode($miembros, true);

        foreach($decodes as &$item){
            $item["id"]=(int)$item["id"];
        }
        
        $encodes = json_encode($decodes);

        $solicitud = [
            $iPersId,
            $encodes,
            $cGrupoNombre,
            $cGrupoDescripcion,
        ];
        
        $query = 'EXEC com.Sp_INS_grupo '.str_repeat('?,',count($solicitud)-1).'?';

        try {
            $data = DB::select($query, $solicitud);
            return ResponseHandler::success($data);
        } catch (Exception $e) {
            return ResponseHandler::error("Error para obtener Datos ",500,$e->getMessage());
        }
    }
    public function obtenerDatosMiembros(Request $request){
        // mostrar datos de estudiantes para miembros de grupo
        $opcion = $request->opcion;
        $iIieeId = $request->iIieeId ?? NULL;
        $iYAcadId = $request->iYAcadId ?? NULL;
        $iSedeId = $request->iSedeId ?? NULL;
        
        //  la opcion 1 muestra los estudiantes de la institucion
        $solicitud = [
            $opcion,
            $iIieeId,
            $iYAcadId,
            $iSedeId
        ];
        $query = 'EXEC acad.Sp_SEL_estudianteXdocenteXespecialista '.str_repeat('?,',count($solicitud)-1).'?';
        $data = DB::select($query, $solicitud);
        try {
            $data = DB::select($query, $solicitud);
            return ResponseHandler::success($data);
        } catch (Exception $e) {
            return ResponseHandler::error("Error para obtener Datos ",500,$e->getMessage());
        }
    }
}
