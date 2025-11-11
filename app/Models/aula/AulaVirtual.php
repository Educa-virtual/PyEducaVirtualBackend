<?php

namespace App\Models\aula;

use App\Helpers\VerifyHash;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AulaVirtual extends Model
{
    public static function obtenerSesiones(Request $request){

        $parametros = [
            $request->iSilaboId,
        ];

        $solicitud = str_repeat('?,', count($parametros)-1).'?';
        $procedimiento = "EXEC aula.SP_SEL_obtenerSesionesArea ".$solicitud;

        $data = DB::select($procedimiento, $parametros);
        return $data;
    }
    public static function obtenerSesionesArea(Request $request){

        $iDocenteId = VerifyHash::decodes($request->iDocenteId);

        $parametros = [
            $iDocenteId
            ,$request->iYAcadId
            ,$request->iSedeId
        ];

        $solicitud = str_repeat('?,', count($parametros)-1).'?';
        $procedimiento = "EXEC aula.SP_SEL_obtenerActividadSesionesArea ".$solicitud;

        $data = DB::select($procedimiento, $parametros);
        return $data;
    }
    public static function obtenerProgramacionActividadArea(Request $request){

        $parametros = [
            $request->iContenidoSemId
        ];

        $solicitud = str_repeat('?,', count($parametros)-1).'?';
        $procedimiento = "EXEC aula.SP_SEL_obtenerProgramacionActividadArea ".$solicitud;
        $data = DB::select($procedimiento, $parametros);

        $archivoBlade  = "reporte_actividades_academicas";
        $tempPath = storage_path('app\\' . $archivoBlade);
        file_put_contents($tempPath, $archivoBlade);
        $exePath   = env('WEASYPRINT_PATH');
        $inputHtml = storage_path('app\\' . $archivoBlade);
        $outputPdf = storage_path('app\\' . $archivoBlade.'.pdf');
        $cmd = "\"{$exePath}\" \"{$inputHtml}\" \"{$outputPdf}\"";
        $output = shell_exec($cmd . ' 2>&1');
        if (!file_exists($outputPdf)) {
            throw new Exception("Error generando PDF: {$output}");
        }
        unlink($inputHtml);
        return $outputPdf;


        return $data;
    }
}
