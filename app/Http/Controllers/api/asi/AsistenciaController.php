<?php

namespace App\Http\Controllers\api\asi;

use App\Http\Controllers\Controller;
use DateTime;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelPdf\Enums\Orientation;
use Spatie\LaravelPdf\Facades\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
    public function list(Request $request)
    {
        if ($request->iCursoId) {
            $iCursoId = $this->hashids->decode($request->iCursoId);
            $iCursoId = count($iCursoId) > 0 ? $iCursoId[0] : $iCursoId;
        }
        $solicitud = [
            $request->opcion,
            $iCursoId ?? NULL,
            $request->dtCtrlAsistencia ?? NULL,
            $request->asistencia_json ?? NULL,
            $request->iSeccionId ?? NULL,
            $request->iYAcadId ?? NULL,
            $request->iNivelGradoId ?? NULL,
            $request->iDocenteId ?? NULL,
            $request->iGradoId ?? NULL,
            $request->inicio ?? NULL,
            $request->fin ?? NULL,
        ];

        $query = DB::select("execute asi.Sp_CRUD_control_asistencias ?,?,?,?,?,?,?,?,?,?,?", $solicitud);

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
    public function report(Request $request)
    {

        $primera_fecha = new DateTime();
        $primerDia = $primera_fecha->modify("first day of this month");
        $ultima_fecha = new DateTime();
        $ultimoDia = $ultima_fecha->modify("last day of this month");

        $primera_fecha = $primerDia->format('Y-m-d');
        $tiempo = strtotime($primera_fecha);
        $ultimo = $ultimoDia->format('d');

        $nombre_dia = date("l", $tiempo);

        $dia_semana = [
            'Sunday' => 'D',
            'Monday' => 'L',
            'Tuesday' => 'M',
            'Wednesday' => 'M',
            'Thursday' => 'J',
            'Friday' => 'V',
            'Saturday' => 'S'
        ];

        $dia_elegido  = $dia_semana[$nombre_dia];
        $dias       = ['D', 'L', 'M', 'M', 'J', 'V', 'S'];
        $indice   = array_search($dia_elegido, $dias);

        $principal = array_slice($dias, $indice);
        $restante = array_slice($dias, 0, $indice);

        $unir_dias = array_merge($principal, $restante);

        $solicitud = [
            $request->opcion ?? 'REPORTE_MENSUAL',
            $request->iCursoId ?? 1,
            $request->dtCtrlAsistencia ?? '2024-11-01',
            $request->asistencia_json ?? 1,
            $request->iSeccionId ?? 2,
            $request->iYAcadId ?? 3,
            $request->iNivelGradoId ?? 1,
            $request->iDocenteId ?? 1,
            $request->iGradoId ?? NULL,
            $request->inicio ?? NULL,
            $request->fin ?? NULL,
        ];

        $query = DB::select("execute asi.Sp_CRUD_control_asistencias ?,?,?,?,?,?,?,?,?,?,?", $solicitud);

        $json_registro = [];

        for ($i = 1; $i <= $ultimo; $i++) {
            $json_registro[] = ["diaMes" => strval($i), "cTipoAsiLetra" => ""];
        }

        foreach ($query as $index => $valor) {
            $registro = json_decode($valor->diasAsistencia);
            $paquete = [];
            foreach ($registro as $fila) {
                $paquete[] = $fila->diaMes;
            }

            $filtrar = array_filter($json_registro, function ($valor) use ($paquete) {
                return !in_array($valor["diaMes"], $paquete);
            });

            $convertir = json_decode(json_encode($registro), true);

            $unir = array_merge($filtrar, $convertir);

            usort($unir, function ($a, $b) {
                return $a["diaMes"] > $b["diaMes"] ? 1 : -1;
            });

            $valor->diasAsistencia = $unir;
        }

        $respuesta = [
            "ultimodia" => $ultimo,
            "query" => $query,
            "dias_Semana" => $unir_dias,
            "year" => "2024",
            "docente" => "RICARDO GERMAN AGIP RUBIO",
            "mes" => "2024-10-01 2024-10-31",
            "modular" => "000005600",
            "dre" => "DRE MOQUEGUA UGEL",
            "fecha_reporte" => "2024-10-01",
            "fecha_cierre" => "2024-10-31",
            "nivel" => "SECUNDARIO",
            "periodo" => "",
            "grado" => "1ro.",
            "seccion" => "Sección A",
            "turno" => "Mañana",
        ];

        $pdf = Pdf::view('asistencia_reporte_mensual', $respuesta)
            ->orientation(Orientation::Landscape)
            ->name('silabus.pdf');
        return $pdf;
    }
    public function reportToExcel()
    {
        return 1;
    }
    public function reporte_personalizado(Request $request)
    {
        $request['valorBusqueda'] = is_null($request->valorBusqueda)
            ? null
            : (is_numeric($request->valorBusqueda)
                ? $request->valorBusqueda
                : ($this->hashids->decode($request->valorBusqueda)[0] ?? null));

        $request['iCursoId'] = is_null($request->iCursoId)
            ? null
            : (is_numeric($request->iCursoId)
                ? $request->iCursoId
                : ($this->hashids->decode($request->iCursoId)[0] ?? null));

        $request['iSeccionId'] = is_null($request->iSeccionId)
            ? null
            : (is_numeric($request->iSeccionId)
                ? $request->iSeccionId
                : ($this->hashids->decode($request->iSeccionId)[0] ?? null));

        $request['iYAcadId'] = is_null($request->iYAcadId)
            ? null
            : (is_numeric($request->iYAcadId)
                ? $request->iYAcadId
                : ($this->hashids->decode($request->iYAcadId)[0] ?? null));

        $request['iGradoId'] = is_null($request->iGradoId)
            ? null
            : (is_numeric($request->iGradoId)
                ? $request->iGradoId
                : ($this->hashids->decode($request->iGradoId)[0] ?? null));

        $request['iDocenteId'] = is_null($request->iDocenteId)
            ? null
            : (is_numeric($request->iDocenteId)
                ? $request->iDocenteId
                : ($this->hashids->decode($request->iDocenteId)[0] ?? null));

        $request['iNivelGradoId'] = is_null($request->iNivelGradoId)
            ? null
            : (is_numeric($request->iNivelGradoId)
                ? $request->iNivelGradoId
                : ($this->hashids->decode($request->iNivelGradoId)[0] ?? null));

        $inicio = $request['id'][0];
        $fin = $request['id'][1];
        // $inicio = "2024-11-01";
        // $fin = "2024-12-25";

        $year = date("Y");
        $convertir_year = strtotime($year);
        $years = date('Y', $convertir_year);

        $fechas = [];

        $fecha_inicio = new DateTime($inicio);
        $fecha_fin = new DateTime($fin);

        $meses = $fecha_inicio->diff($fecha_fin);
        $meses_restantes = $meses->m;

        $solicitud = [
            $request->opcion ?? 'REPORTE_PERSONALIZADO',
            $request['iCursoId'] ?? NULL,
            $request->dtCtrlAsistencia ?? '2024-11-01',
            $request->asistencia_json ?? 1,
            $request['iSeccionId'] ?? NULL,
            $request['iYAcadId'] ?? NULL,
            $request['iNivelGradoId'] ?? NULL,
            $request['iDocenteId'] ?? NULL,
            $request['iGradoId'] ?? NULL,
            $inicio,
            $fin,
        ];

        $query = DB::select("execute asi.Sp_CRUD_control_asistencias ?,?,?,?,?,?,?,?,?,?,?", $solicitud);

        $nombre_mes = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

        $dias       = ['D', 'L', 'M', 'M', 'J', 'V', 'S'];

        for ($i = 0; $i <= $meses_restantes; $i++) {

            $numero_mes = intval(date("m", strtotime($inicio . "+ " . $i . " month")));    // Se extrae el mes y se aumenta 1 mes
            $convertir = new DateTime($years . "-" . $numero_mes);
            $ultimo = $convertir->modify("last day of this month"); // Obtenemos el ultimo dia del mes
            $extraer_fecha = date($years . '-' . $numero_mes . '-01');
            $dia_indice = date('w', strtotime($extraer_fecha));
            $mes = $ultimo->format('m');   // Dar formato al mes
            $dia = $ultimo->format('d');
            $mes_calendario = $nombre_mes[$numero_mes - 1];  // Obtener el nombre del mes

            $fechas[$i]["mes_calendario"] = $mes_calendario;
            $fechas[$i]["mes"] = $mes;
            $fechas[$i]["ultimo_dia"] = $dia;
            $fechas[$i]["dia"] = $dia_indice;

            foreach ($query as $key => $sql) {

                $fechas[$i]["nombre"][$key] = $sql->completoalumno;
                $verificar = json_decode($sql->diasAsistencia);
                $ver = array_column($verificar, "diaMes");

                for ($j = 1; $j <= $fechas[$i]["ultimo_dia"]; $j++) {
                    $analizar = $mes . "-" . str_pad($j, 2, "0", STR_PAD_LEFT);

                    if (in_array($analizar, $ver)) {
                        $index = array_search($analizar, $ver);
                        $fechas[$i]["asistido"][$key][] = $verificar[$index]->cTipoAsiLetra;
                    } else {
                        $fechas[$i]["asistido"][$key][] = "";
                    }
                }
            }
        }


        $respuesta = [
            "year" => date('Y'),
            "docente" => $request->nombrecompleto,
            "mes" => $inicio . " - " . $fin,
            "modular" => "000005600",
            "dre" => "DRE MOQUEGUA UGEL",
            "fecha_reporte" => date('Y-m-d'),
            "fecha_cierre" => "--",
            "nivel" => $request->cNivelTipoNombre,
            "grado" => $request->cGradoAbreviacion,
            "ciclo" => $request->cCicloRomanos,
            "seccion" => $request->cSeccion,
            "turno" => "Mañana",
            "fecha_actual" => "2024-11-15",
            "dias" => $dias,
            "respuesta" => $fechas
        ];

        $pdf = Pdf::view('asistencia_reporte_personalizado', $respuesta)
            ->orientation(Orientation::Landscape)
            ->name('silabus.pdf');
        return $pdf;
        //return view("asistencia_reporte_personalizado",$respuesta);
    }

    public function reporteAsistenciaGeneral(Request $request)
    {

        $solicitud = [
            $request->opcion ?? 'REPORTE_DIARIO',
            $request->iCursoId ?? 1,
            $request->dtCtrlAsistencia ?? '2024-11-01',
            $request->asistencia_json ?? 1,
            $request->iSeccionId ?? 2,
            $request->iYAcadId ?? 3,
            $request->iNivelGradoId ?? 1,
            $request->iDocenteId ?? 1,
        ];

        switch ($request->opcion) {
            case 'reporte-diario':
                $query = DB::select("execute asi.Sp_CRUD_control_asistencias ?,?,?,?,?,?,?,?", $solicitud);
                $pdf = Pdf::view('asistencia_reporte_mensual', '')
                    ->orientation(Orientation::Landscape)
                    ->name('silabus.pdf');
                return $pdf;
                break;
            case 'reporte-semanal':

                break;
            case 'reporte-mensual':

                break;
            case 'reporte-personalizado':

                break;
        }
    }
}
