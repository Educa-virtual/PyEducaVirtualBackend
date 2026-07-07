<?php

namespace App\Http\Controllers\asi;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Helpers\ResponseHandler;
use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use App\Models\asi\AsistenciaAdministrativa;
use App\Models\asi\Codigo;
use App\Services\asi\AsistenciaGeneralService;
use App\Services\asi\ControlAsistenciaService;
use DateTime;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class AsistenciaController extends Controller
{
    protected $hashids;

    protected $iCursoId;

    protected $iSeccionId;

    protected $iYAcadId;

    protected $iNivelGradoId;

    protected $iDocenteId;

    public function __construct()
    {
        $this->hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
    }

    public function obtenerCodigo(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', [[Perfil::AUXILIAR]]);
            $data = Codigo::obtenerCodigo($request);

            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarAsistenciaEstudiante(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', [[Perfil::AUXILIAR]]);
            $data = AsistenciaAdministrativa::guardarAsistenciaEstudiante($request);

            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarAsistenciaGeneral(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', [[Perfil::AUXILIAR]]);
            $data = AsistenciaAdministrativa::guardarAsistenciaGeneral($request);
            AsistenciaGeneralService::notificarApoderadosInasistenciaGeneral($request);

            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function buscarAlumnos(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', [[Perfil::AUXILIAR]]);
            $data = AsistenciaAdministrativa::buscarAlumnos($request);

            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function buscarAsisnteciaGeneral(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', [[Perfil::AUXILIAR]]);
            $data = AsistenciaAdministrativa::buscarAsisnteciaGeneral($request);

            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarGrupo(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', [[Perfil::AUXILIAR]]);
            $data = AsistenciaAdministrativa::guardarHorarioInstitucion($request);

            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarPersonalGrupo(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', [[Perfil::AUXILIAR]]);
            $data = AsistenciaAdministrativa::guardarPersonalInstitucion($request);

            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function ActualizarGrupo(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', [[Perfil::AUXILIAR]]);
            $data = AsistenciaAdministrativa::actualizarHorarioInstitucion($request);

            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function buscarPersonalInstitucion(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', [[Perfil::AUXILIAR]]);
            $data = AsistenciaAdministrativa::buscarPersonalInstitucion($request);

            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verificarGrupoAsistencia(Request $request)
    {

        $iSedeId = $request['iSedeId'];

        if (empty($iSedeId)) {
            $mensaje = 'No se envio datos de la institucion';

            return ResponseHandler::error('Error para obtener Datos', 400, $mensaje);
        }

        $enviar = [
            $iSedeId,
        ];

        $query = 'EXEC asi.Sp_SEL_asistenciaGrupos ?';

        try {
            $data = DB::select($query, $enviar);

            return ResponseHandler::success($data);
        } catch (Exception $e) {
            return ResponseHandler::error('Error para obtener Datos ', 500, $e->getMessage());
        }

    }

    public function verificarHorarioAsistencia(Request $request)
    {

        $iSedeId = $request['iSedeId'];
        $iYAcadId = $request['iYAcadId'];

        if (empty($iSedeId)) {
            $mensaje = 'No se envio datos de la institucion';

            return ResponseHandler::error('Error para obtener Datos', 400, $mensaje);
        }

        $enviar = [
            $iSedeId,
            $iYAcadId,
        ];

        $query = 'EXEC asi.Sp_SEL_asistenciaGruposHorarios ?,?';

        try {
            $data = DB::select($query, $enviar);

            return ResponseHandler::success($data);
        } catch (Exception $e) {
            return ResponseHandler::error('Error para obtener Datos ', 500, $e->getMessage());
        }

    }

    public function buscarHorarioInstitucion(Request $request)
    {
        try {
            // Gate::authorize('tiene-perfil', [[Perfil::AUXILIAR]]);
            $data = AsistenciaAdministrativa::buscarHorarioInstitucion($request);

            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    // Decodifica los id enviados por el frontend
    private function decodificar($id)
    {
        return is_null($id) ? null : (is_numeric($id) ? $id : ($this->hashids->decode($id)[0] ?? null));
    }

    // Obtener las fechas de las areas curriculares para registrar la asistencia
    public function obtenerDetallesCurricular(Request $request)
    {
        $iGradoId = $request['iGradoId'];
        $iCursoId = $request['iCursoId'];
        $iCicloId = $request['iCicloId'];
        $iSeccionId = $request['iSeccionId'];
        $iNivelId = $request['iNivelId'];

        $solicitud = [
            $iGradoId,
            $iSeccionId,
            $iCicloId,
            $iCursoId,
            $iNivelId,
        ];

        $query = 'EXEC acad.Sp_SEL_detalles_curriculares '.str_repeat('?,', count($solicitud) - 1).'?';

        try {
            $data = DB::select($query, $solicitud);

            return ResponseHandler::success($data);
        } catch (Exception $e) {
            return ResponseHandler::error('Error para obtener Datos ', 500, $e->getMessage());
        }

    }

    public function obtenerCursoHorario(Request $request)
    {

        $iSedeId = $request['iSedeId'];
        $iIieeId = $request['iIieeId'];
        $iCursoId = $request['iCursoId'];
        $iYAcadId = $request['iYAcadId'];
        $iDocenteId = VerifyHash::decodes($request['iDocenteId']);
        $iSeccionId = $request['iSeccionId'];
        $iNivelGradoId = $request['iNivelGradoId'];
        $iGradoId = $request['iGradoId'];
        $idDocCursoId = $request['idDocCursoId'];
        $iCicloId = $request['iCicloId'];

        $solicitud = [
            1,
            $iDocenteId ?? null,
            $iYAcadId ?? null,
            $iCursoId ?? null,
            $iSeccionId ?? null,
            $iNivelGradoId ?? null,
            $iGradoId ?? null,
            $idDocCursoId ?? null,
            $iCicloId ?? null,
            $iSedeId ?? null,
            $iIieeId ?? null,
        ];

        $query = 'EXEC acad.Sp_SEL_buscar_cursos_horario '.str_repeat('?,', count($solicitud) - 1).'?';
        try {
            $data = DB::select($query, $solicitud);

            return ResponseHandler::success($data);
        } catch (Exception $e) {
            return ResponseHandler::error('Error para obtener Datos ', 500, $e->getMessage());
        }

        return new JsonResponse($response, $estado);
    }

    public function obtenerAsistencia(Request $request)
    {
        // Se Decodifica los id hasheados que son enviados por el frontend
        $idDocCursoId = $request['idDocCursoId'];
        $iGradoId = $request['iGradoId'];
        $iIieeId = $request['iIieeId'];
        $iSedeId = $request['iSedeId'];
        $iCursoId = $request['iCursoId'];
        $iYAcadId = $request['iYAcadId'];
        $iSeccionId = $request['iSeccionId'];
        $iNivelGradoId = $request['iNivelGradoId'];
        $iDocenteId = $this->decodificar($request['iDocenteId']);

        $solicitud = [
            $iGradoId ?? null,
            $iIieeId ?? null,
            $iSedeId ?? null,
            $iCursoId ?? null,
            $iYAcadId ?? null,
            $iSeccionId ?? null,
            $iNivelGradoId ?? null,
            $iDocenteId ?? null,
            $idDocCursoId ?? null,
        ];

        $query = 'execute asi.Sp_SEL_fechas_asistencia '.str_repeat('?,', count($solicitud) - 1).'?';

        try {
            $data = DB::select($query, $solicitud);
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $data,
            ];

            $estado = 200;

        } catch (Exception $e) {
            $response = [
                'validated' => true,
                'message' => $e->getMessage(),
                'data' => [],
            ];
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }

    public function obtenerEstudiante(Request $request)
    {
        // Se Decodifica los id hasheados que son enviados por el frontend
        $iCursoId = $this->decodificar($request['iCursoId']);
        $iYAcadId = $this->decodificar($request['iYAcadId']);
        $iDocenteId = $this->decodificar($request['iDocenteId']);
        $iSeccionId = $this->decodificar($request['iSeccionId']);
        $iNivelGradoId = $this->decodificar($request['iNivelGradoId']);

        $solicitud = [
            $request->opcion,
            $iCursoId,
            $request->dtCtrlAsistencia ?? null,
            $request->asistencia_json ?? null,
            $iSeccionId,
            $iYAcadId,
            $iNivelGradoId ?? null,
            $iDocenteId,
            $request->iGradoId ?? null,
            $request->iSedeId ?? null,
            $request->iIieeId ?? null,
            $request->idDocCursoId ?? null,
            $request->inicio ?? null,
            $request->fin ?? null,
        ];
        $consulta = 'execute asi.Sp_SEL_control_asistencias '.str_repeat('?,', count($solicitud) - 1).'?';
        $query = DB::select($consulta, $solicitud);
        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 200;

        } catch (Exception $e) {
            $response = [
                'validated' => true,
                'message' => $e->getMessage(),
                'data' => [],
            ];
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }

    public function obtenerFestividad(Request $request)
    {
        $solicitud = [
            'buscar_festividades',
        ];

        $query = DB::select('EXECUTE acad.Sp_SEL_fechas_importantes ?', $solicitud);

        try {
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 200;
        } catch (Exception $e) {
            $response = [
                'validated' => true,
                'message' => $e->getMessage(),
                'data' => [],
            ];
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }

    public function guardarAsistencia(Request $request)
    {
        $iCursoId = $this->decodificar($request['iCursoId']);
        $iYAcadId = $this->decodificar($request['iYAcadId']);
        $iDocenteId = $this->decodificar($request['iDocenteId']);
        $iSeccionId = $this->decodificar($request['iSeccionId']);
        $idDocCursoId = $this->decodificar($request['idDocCursoId']);
        $iNivelGradoId = $this->decodificar($request['iNivelGradoId']);
        $iSedeId = $this->decodificar($request['iSedeId']);
        $archivos = $request->file('archivos');

        $asistencia = json_decode($request->asistencia_json, true);
        $ruta = 'justificaciones/'.$iDocenteId;
        if ($archivos) {
            foreach ($archivos as $index => $archivo) {
                $documento = Storage::disk('public')->put($ruta, $archivo);
                $asistencia[$index]['justificacion'] = $ruta.'/'.basename($documento);
            }
        }
        $solicitud = [
            $request->opcion,
            $iCursoId,
            $request->dtCtrlAsistencia ?? null,
            json_encode($asistencia) ?? null,
            $iSeccionId,
            $iYAcadId,
            $iNivelGradoId ?? null,
            $iDocenteId,
            $request->iGradoId ?? null,
            $idDocCursoId ?? null,
            $iSedeId,
        ];

        $enviar = str_repeat('?,', count($solicitud) - 1).'?';
        $tabla = 'execute asi.Sp_INS_control_asistencias '.$enviar;

        try {
            $query = DB::select($tabla, $solicitud);
            ControlAsistenciaService::notificarApoderadosInasistenciaCurso($solicitud);
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 200;
        } catch (Exception $e) {
            $response = [
                'validated' => true,
                'message' => $e->getMessage(),
                'data' => [],
            ];
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }

    public function report(Request $request)
    {

        $iDocenteId = VerifyHash::decodes($request->iDocenteId);
        $inicio = $request['id'];
        $fecha_inicial = str_pad($inicio, 2, '0', STR_PAD_LEFT);
        $year_actual = date('Y');
        $combinar = $year_actual.'-'.$fecha_inicial.'-01';
        $primera_fecha = new DateTime($combinar);
        $primerDia = $primera_fecha->modify('first day of this month');
        $ultima_fecha = new DateTime($combinar);
        $ultimoDia = $ultima_fecha->modify('last day of this month');

        $primera_fecha = $primerDia->format('Y-m-d');
        $tiempo = strtotime($primera_fecha);
        $ultimo = $ultimoDia->format('d');
        $finMes = $year_actual.'-'.$fecha_inicial.'-'.$ultimo;

        $nombre_dia = date('l', $tiempo);

        $dia_semana = [
            'Sunday' => 'D',
            'Monday' => 'L',
            'Tuesday' => 'M',
            'Wednesday' => 'M',
            'Thursday' => 'J',
            'Friday' => 'V',
            'Saturday' => 'S',
        ];
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $dia_elegido = $dia_semana[$nombre_dia];
        $dias = ['D', 'L', 'M', 'M', 'J', 'V', 'S'];
        $indice = array_search($dia_elegido, $dias);

        $principal = array_slice($dias, $indice);
        $restante = array_slice($dias, 0, $indice);

        $unir_dias = array_merge($principal, $restante);

        $solicitud = [
            $request->opcion ?? 'REPORTE_MENSUAL',
            $request->iCursoId ?? null,
            $request->dtCtrlAsistencia ?? null,
            $request->asistencia_json ?? null,
            $request->iSeccionId ?? null,
            $request->iYAcadId ?? null,
            $request->iNivelGradoId ?? null,
            $iDocenteId ?? null,
            $request->iGradoId ?? null,
            $request->iSedeId ?? null,
            $request->iIieeId ?? null,
            $request->idDocCursoId ?? null,
            $request->inicio ?? $combinar,
            $finMes ?? null,
        ];

        $consulta = 'execute asi.Sp_SEL_control_asistencias '.str_repeat('?,', count($solicitud) - 1).'?';
        $query = DB::select($consulta, $solicitud);
        $json_registro = [];

        for ($i = 1; $i <= $ultimo; $i++) {
            $json_registro[] = ['diaMes' => intval($i), 'cTipoAsiLetra' => ''];
        }

        $json_asistencia = json_decode($query[0]->asistencia, true);

        foreach ($json_asistencia as &$valor) {

            $registro = isset($valor['diasAsistencia']) ? $valor['diasAsistencia'] : [];
            $paquete = [];

            foreach ($registro as &$fila) {
                $fila['diaMes'] = intval($fila['diaMes']);
                $paquete[] = intval($fila['diaMes']);
            }

            $filtrar = array_filter($json_registro, function ($valor) use ($paquete) {
                return ! in_array(intval($valor['diaMes']), $paquete);
            });

            $convertir = $registro;

            if (! is_array($convertir)) {
                $convertir = [];
            }

            $unir = array_merge($filtrar, $convertir);
            usort($unir, function ($a, $b) {
                return $a['diaMes'] > $b['diaMes'] ? 1 : -1;
            });

            $valor['diasAsistencia'] = $unir;
        }

        $json_institucion = json_decode($query[0]->institucion, true);
        $logo = $json_institucion[0]['cIieeLogo'];
        if (! empty($logo)) {
            $verLogo = explode(',', $logo);
            $base64Image = str_replace(["\r", "\n"], '', $verLogo[1]);
        } else {
            $base64Image = '';
        }
        if (base64_decode($base64Image, true) === false) {
            $logo = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';
        }

        $respuesta = [
            'iiee' => $json_institucion[0]['cIieeNombre'],
            'logo' => $logo,
            'docente' => strtoupper($query[0]->docentes),
            'area_curricular' => strtoupper($query[0]->curso),
            'grado' => $request->cGradoAbreviacion,
            'seccion' => $request->cSeccionNombre,
            'modular' => $json_institucion[0]['cIieeCodigoModular'],
            'nivel' => $request->cNivelTipoNombre,
            'query' => $json_asistencia,
            'ultimodia' => $ultimo,
            'dias_Semana' => $unir_dias,
            'mes' => $meses[$inicio - 1],
            'inicio' => $combinar,
            'fin' => $finMes,
            'ciclo' => $request->cCicloRomanos,
            'yearAcademico' => $query[0]->yearAcademico,
            'nombreUgel' => $json_institucion[0]['cUgelNombre'],
        ];

        $htmlcontent = view('asistencia_reporte_mensual', compact('respuesta'))->render();

        $archivoBlade = 'asistencia_reporte_mensual';
        $archivoHtml = $archivoBlade.'.html';

        $tempPath = storage_path('app/'.$archivoHtml);
        file_put_contents($tempPath, $htmlcontent);

        $exePath = env('WEASYPRINT_PATH');
        $inputHtml = storage_path('app/'.$archivoHtml);
        $outputPdf = storage_path('app/'.$archivoBlade.'.pdf');

        $cmd = "\"{$exePath}\" \"{$inputHtml}\" \"{$outputPdf}\"";
        $output = shell_exec($cmd.' 2>&1');

        if (! file_exists($outputPdf)) {
            throw new Exception("Error generando PDF: {$output}");
        }

        if (file_exists($inputHtml)) {
            unlink($inputHtml);
        }

        return response()->download($outputPdf)->deleteFileAfterSend(true);

    }

    public function reporte_diario(Request $request)
    {
        $iDocenteId = VerifyHash::decodes($request->iDocenteId);
        $inicio = $request['id'];
        $fin = $request['id'];

        $year = date('Y');
        $convertir_year = strtotime($year);
        $years = date('Y', $convertir_year);

        $fechas = [];
        $solicitud = [
            $request->opcion ?? 'REPORTE_PERSONALIZADO',
            $request->iCursoId ?? null,
            $request->dtCtrlAsistencia ?? null,
            $request->asistencia_json ?? 1,
            $request->iSeccionId ?? null,
            $request->iYAcadId ?? null,
            $request->iNivelGradoId ?? null,
            $iDocenteId ?? null,
            $request->iGradoId ?? null,
            $request->iSedeId ?? null,
            $request->iIieeId ?? null,
            $request->idDocCursoId ?? null,
            $inicio,
            $fin,
        ];

        $consulta = 'execute asi.Sp_SEL_control_asistencias '.str_repeat('?,', count($solicitud) - 1).'?';
        $query = DB::select($consulta, $solicitud);
        $nombre_mes = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

        $dias = ['D', 'L', 'M', 'M', 'J', 'V', 'S'];

        $numero_mes = intval(date('m', strtotime($inicio.'+ '. 0 .' month')));    // Se extrae el mes y se aumenta 1 mes
        $convertir = new DateTime($years.'-'.$numero_mes);
        $ultimo = $convertir->modify('last day of this month'); // Obtenemos el ultimo dia del mes
        $extraer_fecha = date($years.'-'.$numero_mes.'-01');
        $dia_indice = date('w', strtotime($extraer_fecha));
        $mes = $ultimo->format('m');   // Dar formato al mes
        $dia = $ultimo->format('d');
        $mes_calendario = $nombre_mes[$numero_mes - 1];  // Obtener el nombre del mes

        $fechas[0]['mes_calendario'] = $mes_calendario;
        $fechas[0]['mes'] = $mes;
        $fechas[0]['ultimo_dia'] = $dia;
        $fechas[0]['dia'] = $dia_indice;

        $json_institucion = json_decode($query[0]->institucion, true);
        $json_asistencia = json_decode($query[0]->asistencia, true);

        $logo = $json_institucion[0]['cIieeLogo'];
        if (! empty($logo)) {
            $verLogo = explode(',', $logo);
            $base64Image = str_replace(["\r", "\n"], '', $verLogo[1]);
        } else {
            $base64Image = '';
        }

        if (base64_decode($base64Image, true) === false) {
            $logo = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';
        }

        $datos = [];
        foreach ($json_asistencia as $key => $indice) {
            $datos['lista'][$key][] = $indice['completoalumno'];
            $valor = $indice['diasAsistencia'] ?? null;
            $datos['lista'][$key][] = $valor == null ? '' : $valor[0]['asistencia'];
        }

        $formato_fecha = new DateTime($inicio);
        $fecha_fija = $formato_fecha->format('Y-m-d');

        $respuesta = [
            'iiee' => $json_institucion[0]['cIieeNombre'],
            'docente' => strtolower($query[0]->docentes),
            'mes' => strtolower($fechas[0]['mes_calendario']),
            'modular' => $json_institucion[0]['cIieeCodigoModular'],
            'nivel' => $request->cNivelTipoNombre,
            'grado' => $request->cGradoAbreviacion,
            'ciclo' => $request->cCicloRomanos,
            'seccion' => $request->cSeccionNombre,
            'area_curricular' => strtolower($query[0]->curso),
            'fecha_actual' => $fecha_fija,
            'dias' => $dias,
            'datos' => $datos,
            'logo' => $logo,
            'yearAcademico' => $query[0]->yearAcademico,
            'nombreUgel' => $json_institucion[0]['cUgelNombre'],
        ];

        $htmlcontent = view('asistencia_reporte_diario', compact('respuesta'))->render();

        $archivoBlade = 'asistencia_reporte_diario';
        $archivoHtml = $archivoBlade.'.html';

        $tempPath = storage_path('app/'.$archivoHtml);
        file_put_contents($tempPath, $htmlcontent);

        $exePath = env('WEASYPRINT_PATH');
        $inputHtml = storage_path('app/'.$archivoHtml);
        $outputPdf = storage_path('app/'.$archivoBlade.'.pdf');

        $cmd = "\"{$exePath}\" \"{$inputHtml}\" \"{$outputPdf}\"";
        $output = shell_exec($cmd.' 2>&1');

        if (! file_exists($outputPdf)) {
            throw new Exception("Error generando PDF: {$output}");
        }

        if (file_exists($inputHtml)) {
            unlink($inputHtml);
        }

        return response()->download($outputPdf)->deleteFileAfterSend(true);
    }

    public function reporte_personalizado(Request $request)
    {

        $iDocenteId = VerifyHash::decodes($request->iDocenteId);

        $inicio = $request['id'][0];
        $fin = $request['id'][1];

        $year = date('Y');
        $convertir_year = strtotime($year);
        $years = date('Y', $convertir_year);

        $fechas = [];

        $fecha_inicio = new DateTime($inicio);
        $fecha_fin = new DateTime($fin);

        $meses = $fecha_inicio->diff($fecha_fin);
        $meses_restantes = $meses->m;

        $solicitud = [
            $request->opcion ?? 'REPORTE_PERSONALIZADO',
            $request->iCursoId ?? null,
            $request->dtCtrlAsistencia ?? null,
            $request->asistencia_json ?? 1,
            $request->iSeccionId ?? null,
            $request->iYAcadId ?? null,
            $request->iNivelGradoId ?? null,
            $iDocenteId ?? null,
            $request->iGradoId ?? null,
            $request->iSedeId ?? null,
            $request->iIieeId ?? null,
            $request->idDocCursoId ?? null,
            $inicio,
            $fin,
        ];

        $consulta = 'execute asi.Sp_SEL_control_asistencias '.str_repeat('?,', count($solicitud) - 1).'?';
        $query = DB::select($consulta, $solicitud);

        $nombre_mes = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

        $dias = ['D', 'L', 'M', 'M', 'J', 'V', 'S'];

        $json_asistencia = json_decode($query[0]->asistencia, true);

        if ($meses_restantes == 0) {
            $numero_mes = intval(date('m', strtotime($inicio.'+ '. 0 .' month')));    // Se extrae el mes y se aumenta 1 mes
            $convertir = new DateTime($years.'-'.$numero_mes);
            $ultimo = $convertir->modify('last day of this month'); // Obtenemos el ultimo dia del mes
            $extraer_fecha = date($years.'-'.$numero_mes.'-01');
            $dia_indice = date('w', strtotime($extraer_fecha));
            $mes = $ultimo->format('m');   // Dar formato al mes
            $dia = $ultimo->format('d');
            $mes_calendario = $nombre_mes[$numero_mes - 1];  // Obtener el nombre del mes

            $fechas[0]['mes_calendario'] = $mes_calendario;
            $fechas[0]['mes'] = $mes;
            $fechas[0]['ultimo_dia'] = $dia;
            $fechas[0]['dia'] = $dia_indice;
            foreach ($json_asistencia as $key => $sql) {

                $fechas[0]['nombre'][$key] = $sql['completoalumno'];
                $verificar = $sql['diasAsistencia'] ?? [];
                $ver = array_column($verificar, 'diaMes');
                for ($j = 1; $j <= $fechas[0]['ultimo_dia']; $j++) {
                    $analizar = $mes.'-'.str_pad($j, 2, '0', STR_PAD_LEFT);

                    if (in_array($analizar, $ver)) {
                        $index = array_search($analizar, $ver);
                        $fechas[0]['asistido'][$key][] = $verificar[$index]['cTipoAsiLetra'];
                    } else {
                        $fechas[0]['asistido'][$key][] = '';
                    }
                }
            }
        } else {
            for ($i = 0; $i <= $meses_restantes; $i++) {

                $numero_mes = intval(date('m', strtotime($inicio.'+ '.$i.' month')));    // Se extrae el mes y se aumenta 1 mes
                $convertir = new DateTime($years.'-'.$numero_mes);
                $ultimo = $convertir->modify('last day of this month'); // Obtenemos el ultimo dia del mes
                $extraer_fecha = date($years.'-'.$numero_mes.'-01');
                $dia_indice = date('w', strtotime($extraer_fecha));
                $mes = $ultimo->format('m');   // Dar formato al mes
                $dia = $ultimo->format('d');
                $mes_calendario = $nombre_mes[$numero_mes - 1];  // Obtener el nombre del mes

                $fechas[$i]['mes_calendario'] = $mes_calendario;
                $fechas[$i]['mes'] = $mes;
                $fechas[$i]['ultimo_dia'] = $dia;
                $fechas[$i]['dia'] = $dia_indice;

                foreach ($json_asistencia as $key => $sql) {

                    $fechas[$i]['nombre'][$key] = $sql['completoalumno'];
                    $verificar = $sql['diasAsistencia'] ?? [];
                    $ver = array_column($verificar, 'diaMes');
                    for ($j = 1; $j <= $fechas[$i]['ultimo_dia']; $j++) {
                        $analizar = $mes.'-'.str_pad($j, 2, '0', STR_PAD_LEFT);

                        if (in_array($analizar, $ver)) {
                            $index = array_search($analizar, $ver);
                            $fechas[$i]['asistido'][$key][] = $verificar[$index]['cTipoAsiLetra'];
                        } else {
                            $fechas[$i]['asistido'][$key][] = '';
                        }
                    }
                }
            }
        }

        $json_institucion = json_decode($query[0]->institucion, true);

        $logo = $json_institucion[0]['cIieeLogo'];
        if (! empty($logo)) {
            $verLogo = explode(',', $logo);
            $base64Image = str_replace(["\r", "\n"], '', $verLogo[1]);
        } else {
            $base64Image = '';
        }

        if (base64_decode($base64Image, true) === false) {
            $logo = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';
        }

        $respuesta = [
            'logo' => $logo,
            'year' => date('Y'),
            'iiee' => $json_institucion[0]['cIieeNombre'],
            'docente' => strtolower($query[0]->docentes),
            'rango' => date('Y-m-d', strtotime($inicio)).' - '.date('Y-m-d', strtotime($fin)),
            'modular' => $json_institucion[0]['cIieeCodigoModular'],
            'fecha_reporte' => date('Y-m-d H:i:s'),
            'fecha_cierre' => '--',
            'nivel' => $request->cNivelTipoNombre,
            'grado' => $request->cGradoAbreviacion,
            'ciclo' => $request->cCicloRomanos,
            'seccion' => $request->cSeccionNombre,
            'area_curricular' => strtolower($query[0]->curso),
            'fecha_actual' => '2024-11-15',
            'dias' => $dias,
            'fechas' => $fechas,
            'yearAcademico' => $query[0]->yearAcademico,
            'nombreUgel' => $json_institucion[0]['cUgelNombre'],
        ];

        $htmlcontent = view('asistencia_reporte_personalizado', compact('respuesta'))->render();

        $archivoBlade = 'asistencia_reporte_personalizado';
        $archivoHtml = $archivoBlade.'.html';

        $tempPath = storage_path('app/'.$archivoHtml);
        file_put_contents($tempPath, $htmlcontent);

        $exePath = env('WEASYPRINT_PATH');
        $inputHtml = storage_path('app/'.$archivoHtml);
        $outputPdf = storage_path('app/'.$archivoBlade.'.pdf');

        $cmd = "\"{$exePath}\" \"{$inputHtml}\" \"{$outputPdf}\"";
        $output = shell_exec($cmd.' 2>&1');

        if (! file_exists($outputPdf)) {
            throw new Exception("Error generando PDF: {$output}");
        }

        if (file_exists($inputHtml)) {
            unlink($inputHtml);
        }

        return response()->download($outputPdf)->deleteFileAfterSend(true);
    }

    public function descargarJustificacion(Request $request)
    {
        $cJustificar = $request->cJustificar;

        if (! Storage::disk('public')->exists($cJustificar)) {
            throw new Exception('El archivo no existe');
        }

        $archivo = Storage::disk('public')->get($cJustificar);

        return $archivo;

    }
}
