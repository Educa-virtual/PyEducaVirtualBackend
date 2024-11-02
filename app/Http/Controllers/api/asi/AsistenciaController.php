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

class AsistenciaController extends Controller
{
    protected $hashids;
    protected $iCursoId;
    public function __construct(){
        $this->hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
    }
    public function list(Request $request){
        if ($request->iCursoId) {
            $iCursoId = $this->hashids->decode($request->iCursoId);
            $iCursoId = count($iCursoId) > 0 ? $iCursoId[0] : $iCursoId;
        }
        $solicitud = [
            $request->opcion,
            $iCursoId ?? NULL,
            $request->dtCtrlAsistencia ?? NULL,
            $request->asistencia_json ?? NULL,
        ];
        
        $query=DB::select("execute asi.Sp_CRUD_control_asistencias ?,?,?,?", $solicitud);
        
        try{
            $response = [
                'validated' => true, 
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 200;

        } catch(Exception $e){
            $response = [
                'validated' => true, 
                'message' => $e->getMessage(),
                'data' => [],
            ];
            $estado = 500;
        }

        return new JsonResponse($response,$estado);
    }
    public function report(){
       
        $primera_fecha = new DateTime();
        $primerDia = $primera_fecha->modify("first day of this month");
        $ultima_fecha = new DateTime();
        $ultimoDia = $ultima_fecha->modify("last day of this month");

        $primera_fecha = $primerDia->format('Y-m-d');
        $tiempo = strtotime($primera_fecha);
        $ultimo = $ultimoDia->format('d');
        
        $nombre_dia = date("l",$tiempo);

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
        $dias 	  = ['D','L','M','M','J','V','S'];
        $indice   = array_search($dia_elegido,$dias);

        $principal = array_slice($dias,$indice);
        $restante = array_slice($dias,0,$indice);
        
        $unir_dias = array_merge($principal,$restante);

        $solicitud = [
            $request->opcion ?? 'REPORTE_MENSUAL',
            $iCursoId ?? NULL,
            $request->dtCtrlAsistencia ?? NULL,
            $request->asistencia_json ?? NULL,
        ];

        $query=DB::select("execute asi.Sp_CRUD_control_asistencias ?,?,?,?", $solicitud);

        $json_registro = [];
        
        for ($i=1; $i <= $ultimo; $i++) {
            $json_registro [] = ["diaMes"=>strval($i),"cTipoAsiLetra"=>"-"];
        }
        
        foreach ($query as $index => $valor) {
            $registro = json_decode($valor->diasAsistencia);
            $paquete = [];
            foreach($registro as $fila){
                $paquete[] = $fila->diaMes;
            }
            
            $filtrar = array_filter($json_registro,function($valor) use ($paquete){
                return !in_array($valor["diaMes"],$paquete);
            });

            $convertir = json_decode(json_encode($registro),true);

            $unir = array_merge($filtrar,$convertir);
            
            usort($unir, function($a,$b){
                return $a["diaMes"] > $b["diaMes"] ? 1 : -1;
            });

            $valor->diasAsistencia = $unir;
            
        }

        $respuesta = [
            "ultimodia"=>$ultimo,
            "query"=>$query,
            "dias_Semana"=>$unir_dias,
            "year"=>"2024",
            "mes"=>"2024-10-01 2024-10-31",
            "modular"=>"000005600",
            "dre"=>"DRE MOQUEGUA UGEL",
            "fecha_reporte"=>"2024-10-01",
            "fecha_cierre"=>"2024-10-31",
            "nivel"=>"SECUNDARIO",
            "periodo"=>"",
            "grado"=>"",
        ];

        $pdf = Pdf::view('asistencia_reporte_mensual', $respuesta)
            ->orientation(Orientation::Landscape)
            ->name('silabus.pdf');
        return $pdf;
        
    }
}
