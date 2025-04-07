<?php

namespace App\Http\Controllers\Ere;

use App\Http\Controllers\Controller;
use App\Services\LeerExcelService;
use App\Services\ParseSqlErrorService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReporteEvaluacionesController extends Controller
{
    public function importar(Request $request)
    {
        $datos_hojas = LeerExcelService::leer($request);

        $datos_hoja = $this->formatearDatos($datos_hojas);
        $parametros = [
            $request->iSedeId,
            $request->iSemAcadId,
            $request->iYAcadId,
            $request->iCredId,
            $datos_hoja['codigo_modular'],
            $datos_hoja['curso'],
            $datos_hoja['nivel'],
            $datos_hoja['grado'],
            json_encode($datos_hoja['resultados']),
        ];

        if( count($datos_hoja['resultados']) === 0 ) {
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

    private function formatearDatos($hojas)
    {
        if( count($hojas) == 0 ) {
            return [];
        }

        $estudiantes = [];
        $config = $hojas[4];
        $filas = $hojas[3];

        $data['codigo_modular'] = trim($config[9]['C']);
        $data['curso'] = trim($config[7]['H']);
        $data['nivel'] = trim($config[7]['C']);
        $data['grado'] = trim($config[7]['N']);

        // Reemplazar texto a códigos identificadores
        $sexos = [
            'FEMENINO' => 'F',
            'MASCULINO' => 'M',
            'Femenino' => 'F',
            'Masculino' => 'M',
        ];

        foreach($filas as $index_fila => $fila)
        {
            // Extraer datos a partir de la fila 13
            if($index_fila >= 1)
            {
                // Limpiar datos de la fila
                $fila = array_map('trim', $fila);

                // Ignorar filas sin apellido y nombres
                if (($fila['D'] == '') && ($fila['F'] == '')) {
                    continue;
                }

                // Formatear resultados de estudiantes en nuevo array
                $resultados[] = array(
                    'fecha' => Carbon::createFromFormat('d/m/Y', $fila['B'])->format('Y-m-d'),
                    'documento' => $fila['C'],
                    'grado' => $fila['C'],
                    'paterno' => $fila['D'],
                    'materno' => $fila['E'],
                    'nombres' => $fila['F'],
                    'sexo' => $fila['I'],
                    'cod_modular' => $fila['K'],
                    'seccion' => $fila['P'],
                    'respuesta01' => $fila['Q'],
                    'respuesta02' => $fila['R'],
                    'respuesta03' => $fila['S'],
                    'respuesta04' => $fila['T'],
                    'respuesta05' => $fila['U'],
                    'respuesta06' => $fila['V'],
                    'respuesta07' => $fila['W'],
                    'respuesta08' => $fila['X'],
                    'respuesta09' => $fila['Y'],
                    'respuesta10' => $fila['Z'],
                    'respuesta11' => $fila['AA'],
                    'respuesta12' => $fila['AB'],
                    'respuesta13' => $fila['AC'],
                    'respuesta14' => $fila['AD'],
                    'respuesta15' => $fila['AE'],
                    'respuesta16' => $fila['AF'],
                    'respuesta17' => $fila['AG'],
                    'respuesta18' => $fila['AH'],
                    'respuesta19' => $fila['AI'],
                    'respuesta20' => $fila['AJ'],
                    'documento_doccente' => $fila['AN'],
                );
            }
        }

        $data['resultados'] = $resultados;
        
        return $data;
    }
}