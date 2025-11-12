<?php

namespace App\Models\aula;

use App\Helpers\VerifyHash;
use App\Repositories\grl\PersonasRepository;
use App\Services\acad\InstitucionesEducativasService;
use App\Services\acad\YearAcademicosService;
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

        $iPersId = VerifyHash::decodes($request->iPersId);

        $parametros = [
            $request->iContenidoSemId
        ];

        $procedimiento = "EXEC aula.SP_SEL_obtenerProgramasActividades ?";
        $actividades = DB::select($procedimiento, $parametros);

        foreach ($actividades as &$actividad) {
            $actividad->cProgActDescripcion = strip_tags($actividad->cProgActDescripcion);
            $actividad->iEstado = $actividad->iEstado == 2 ? 'Publicado' : 'Finalizado';
        }

        $area = $request->cCursoNombre;
        $grado = $request->cGradoAbreviacion;
        $seccion = $request->cSeccionNombre;
        $periodo = $request->cNumeroPeriodo;

        $persona = PersonasRepository::obtenerPersonaPorId($iPersId);
        $ie = InstitucionesEducativasService::obtenerIeNivel($request->iIieeId);
        $yearAcademico = YearAcademicosService::obtenerYearAcademico($request->iYAcadId);

        $persona = PersonasRepository::obtenerPersonaPorId($iPersId);
        $ie = InstitucionesEducativasService::obtenerIeNivel($request->iIieeId);
        $yearAcademico = YearAcademicosService::obtenerYearAcademico($request->iYAcadId);

        $htmlcontent = view('aula.reporte_actividades_academicas', compact('persona','ie','yearAcademico','grado','seccion','actividades','area','periodo'))->render();

        $archivoBlade = 'reporte_actividades_academicas';
        $archivoHtml = $archivoBlade . '.html';

        $tempPath = storage_path('app/' . $archivoHtml);
        file_put_contents($tempPath, $htmlcontent);

        $exePath = env('WEASYPRINT_PATH');
        $inputHtml = storage_path('app/' . $archivoHtml);
        $outputPdf = storage_path('app/' . $archivoBlade . '.pdf');

        $cmd = "\"{$exePath}\" \"{$inputHtml}\" \"{$outputPdf}\"";
        $output = shell_exec($cmd . ' 2>&1');

        if (!file_exists($outputPdf)) {
            throw new Exception("Error generando PDF: {$output}");
        }

        if (file_exists($inputHtml)) {
            unlink($inputHtml);
        }

        return $outputPdf;
        
    }
}
