<?php

namespace App\Services\apo;

use App\Helpers\VerifyHash;
use App\Models\apo\Apoderado;
use App\Services\acad\InstitucionesEducativasService;
use App\Services\FormatearExcelPadresService;
use App\Services\LeerExcelService;
use App\Services\seg\UsuariosService;
use Exception;
use Illuminate\Http\Request;

class ApoderadosService
{
    public static function obtenerEstudiantesPorApoderado($iPersId)
    {
        $data = Apoderado::selEstudiantesPorApoderado($iPersId);
        foreach ($data as $fila) {
            $fila->iEstudianteId = VerifyHash::encodexId($fila->iEstudianteId);
        }
        return $data;
    }

    public static function estudiantePerteneceApoderado($iPersIdApoderado, $iEstudianteId)
    {
        $data = Apoderado::selEstudianteApoderado($iPersIdApoderado, $iEstudianteId);
        if (!$data) {
            throw new Exception("El estudiante no esta relacionado con el apoderado");
        }
    }

    public static function importarDesdeArchivoExcel(Request $request, $iPersId)
    {
        $dataExcel = LeerExcelService::leer($request);
        $dataFormateada = FormatearExcelPadresService::formatear($dataExcel);

        $detallesCredencial = UsuariosService::obtenerDetallesCredencialEntidad($request->header('iCredEntPerfId'));
        $institucionEducativa = InstitucionesEducativasService::obtenerIePorSede($detallesCredencial->iSedeId);
        if ($institucionEducativa->cIieeCodigoModular!=$dataFormateada['codigo_modular']) {
            throw new Exception("El código modular del archivo no coincide con la institución educativa del usuario.");
        }
        //$json_estudiantes = str_replace("'", "''", json_encode($dataFormateada['estudiantes']));
        Apoderado::insApoderadosDesdeArchivo($dataFormateada['estudiantes'], $iPersId);

    }
}
