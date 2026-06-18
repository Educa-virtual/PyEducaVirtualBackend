<?php

namespace App\Http\Controllers\acad;

use App\Helpers\FormatearMensajeHelper;
use App\Enums\Perfil;
use App\Http\Controllers\Controller;
use App\Models\acad\Estudiante;
use App\Services\acad\MatriculasService;
use App\Services\acad\TiposActividadService;
use App\Services\acad\YearAcademicosService;
use App\Services\apo\ApoderadosService;
use App\Services\aula\ProgramacionActividadesService;
use App\Services\FormatearExcelMatriculasService;
use App\Services\LeerExcelService;
use App\Services\ParseSqlErrorService;
use App\Services\seg\UsuariosService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class EstudiantesController extends Controller
{
    protected $leerExcelService;
    protected $parseSqlErrorService;
    protected $formatearExcelMatriculasService;

    public function __construct()
    {
        $this->leerExcelService = new LeerExcelService();
        $this->parseSqlErrorService = new ParseSqlErrorService();
        $this->formatearExcelMatriculasService = new FormatearExcelMatriculasService();
    }

    public function obtenerCursosXEstudianteAnioSemestre(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ESTUDIANTE]]);
            $data = Estudiante::selObtenerCursoEstudiante($request);
            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarEstudiante(Request $request)
    {
        try {
            $data = Estudiante::insEstudiante($request);
            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarEstudiante(Request $request)
    {
        try {
            $data = Estudiante::updEstudiante($request);
            return FormatearMensajeHelper::ok('Se actualizó la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function listarEstudiantes(Request $request)
    {
        try {
            $data = Estudiante::selEstudiantes($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verEstudiante(Request $request)
    {
        try {
            $data = Estudiante::selEstudiante($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (\Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function importarEstudiantesPadresExcel(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);
            ApoderadosService::importarDesdeArchivoExcel($request, Auth::user()->iPersId);
            return FormatearMensajeHelper::ok('Se han importado los apoderados correctamente');
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function importarEstudiantesMatriculasExcel(Request $request)
    {
        $datos_hojas = LeerExcelService::leer($request);

        $datos_hoja = FormatearExcelMatriculasService::formatear($datos_hojas);

        $json_estudiantes = str_replace("'", "''", json_encode($datos_hoja['estudiantes']));

        $parametros = [
            $request->iSedeId,
            $request->iSemAcadId,
            $request->iYAcadId,
            $request->iCredId,
            $datos_hoja['nivel'],
            $datos_hoja['modalidad'],
            $datos_hoja['turno'],
            $json_estudiantes,
            $datos_hoja['codigo_modular'],
        ];


        if (count($datos_hoja['estudiantes']) === 0) {
            return new JsonResponse(['message' => 'No se encontraron estudiantes', 'data' => []], 500);
        }

        try {
            $data = DB::select('EXEC acad.Sp_INS_estudiantesMatriculasMasivo ?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function existeMatriculaPorAnio($iYAcadId, Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ESTUDIANTE]]);

            $detallesCredencial = UsuariosService::obtenerDetallesCredencialEntidad($request->header('iCredEntPerfId'));
            $params = [Auth::user()->iPersId, $iYAcadId, $detallesCredencial->iSedeId, NULL];
            $matricula =  MatriculasService::obtenerDetalleMatriculaEstudiante($params);
            return FormatearMensajeHelper::ok("Existe", ['existe' => $matricula != null]);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function obtenerCalendario($iYAcadId, Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ESTUDIANTE]]);
            $detallesCredencial = UsuariosService::obtenerDetallesCredencialEntidad($request->header('iCredEntPerfId'));
            $params = [Auth::user()->iPersId, $iYAcadId, $detallesCredencial->iSedeId, NULL];
            $matricula = MatriculasService::obtenerDetalleMatriculaEstudiante($params);
            $cursos = MatriculasService::obtenerCursosMatricula($matricula->iMatrId);
            $tiposActividad = TiposActividadService::obtenerTiposActividad();
            $anioAcademico = YearAcademicosService::obtenerYearAcademico($matricula->iYAcadId);

            $calendario = ProgramacionActividadesService::obtenerCalendarioAcademicoEstudiante($matricula);
            return FormatearMensajeHelper::ok('Se obtuvo el calendario académico', [
                'calendario' => $calendario,
                'cursos' => $cursos,
                'tiposActividad' => $tiposActividad,
                'anioAcademico' => $anioAcademico
            ]);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
    public function obtenerCalendarioEstudiante($iYAcadId,$iPersId,$iSedeId)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::APODERADO]]);
            //$detallesCredencial = UsuariosService::obtenerDetallesCredencialEntidad($request->header('iCredEntPerfId'));
            $params = [Auth::user()->$iPersId, $iYAcadId, $iSedeId, NULL];
            $matricula = MatriculasService::obtenerDetalleMatriculaEstudiante($params);
            $cursos = MatriculasService::obtenerCursosMatricula($matricula->iMatrId);
            $tiposActividad = TiposActividadService::obtenerTiposActividad();
            $anioAcademico = YearAcademicosService::obtenerYearAcademico($matricula->iYAcadId);

            $calendario = ProgramacionActividadesService::obtenerCalendarioAcademicoEstudiante($matricula);
            return FormatearMensajeHelper::ok('Se obtuvo el calendario académico', [
                'calendario' => $calendario,
                'cursos' => $cursos,
                'tiposActividad' => $tiposActividad,
                'anioAcademico' => $anioAcademico
            ]);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
