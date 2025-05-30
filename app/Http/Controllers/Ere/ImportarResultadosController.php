<?php

namespace App\Http\Controllers\Ere;

use App\Http\Controllers\Controller;
use App\Services\LeerExcelService;
use App\Services\ParseSqlErrorService;
use Carbon\Carbon;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ImportarResultadosController extends Controller
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    private function decodeValue($value)
    {
        if (is_null($value)) {
            return null;
        }
        return is_numeric($value) ? $value : ($this->hashids->decode($value)[0] ?? null);
    }

    public function importarOffLine(Request $request)
    {

        $iCursosNivelGradId = in_array($request->iCursosNivelGradId, ['undefined', 'null', null, '', false, 0]) ? null : $request->iCursosNivelGradId;

        
        $parametros = [
            $request->iSedeId,
            $request->iSemAcadId,
            $request->iYAcadId,
            $request->iCredId,
            $this->decodeValue($request->iEvaluacionIdHashed),
            $this->decodeValue($iCursosNivelGradId),
            $request->codigo_modular,
            $request->cCursoNombre,
            $request->tipo,
            $request->cGradoAbreviacion,
            $request->json_resultados
        ];
        try {
            $query_string = "EXEC acad.Sp_INS_importarResultados " . str_repeat("?,", (count($parametros) - 1)) . '?';
            // $data = DB::select('EXEC ere.Sp_INS_importarResultados ?,?,?,?,?,?,?,?,?,?,?', $parametros);
            //$data = DB::select('EXEC ere.Sp_INS_importarResultados ?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'Se obtuvo la información', 'data' => $query_string];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
       // return   $parametros;
        return new JsonResponse($response, $codeResponse);
    }


    public function importar(Request $request)
    {
        // Subir archivo para revisión, desactivar eventualmente
        // $this->subirArchivo($request);

        $datos_hojas = $request['datos_hojas'];

        $datos_hoja = $this->formatearDatos($datos_hojas);

        $curso_nivel_grado = in_array($request->iCursosNivelGradId, ['undefined', 'null', null, '', false, 0]) ? null : $request->iCursosNivelGradId;

        $json_resultados = str_replace("'", "''", json_encode($datos_hoja['resultados']));

        $parametros = [
            $request->iSedeId,
            $request->iSemAcadId,
            $request->iYAcadId,
            $request->iCredId,
            $this->decodeValue($request->iEvaluacionIdHashed),
            $this->decodeValue($curso_nivel_grado),
            $datos_hoja['codigo_modular'],
            $datos_hoja['curso'],
            $datos_hoja['nivel'],
            $datos_hoja['grado'],
            $json_resultados,
        ];

        if (count($datos_hoja['resultados']) === 0) {
            return new JsonResponse(['message' => 'No se encontraron resultados', 'data' => []], 500);
        }

        try {
            // $query_string = "EXEC acad.Sp_INS_importarResultados ".str_repeat("?,", (count($parametros)-1)).'?';
            $data = DB::select('EXEC ere.Sp_INS_importarResultados ?,?,?,?,?,?,?,?,?,?,?', $parametros);
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
        if (count($hojas) == 0) {
            return [];
        }

        $resultados = [];
        $config = $hojas[0];
        $filas = $hojas[1];

        $data['codigo_modular'] = trim($config[10]['C']);
        $data['curso'] = trim($config[8]['H']);
        $data['nivel'] = trim($config[8]['C']);
        $data['grado'] = trim($config[8]['N']);

        // Reemplazar texto a códigos identificadores
        $sexos = [
            'F' => 'F',
            'M' => 'M',
            'FEMENINO' => 'F',
            'MASCULINO' => 'M',
            'MUJER' => 'F',
            'HOMBRE' => 'M',
        ];

        foreach ($filas as $index_fila => $fila) {
            // Extraer datos a partir de la fila 2
            if ($index_fila > 1) {
                // Limpiar datos de la fila
                $fila = array_map('strtoupper', $fila);
                $fila = array_map(function ($string) {
                    $simbolos_invalidos = ['.', ',', '+', '(', ')', ':', ';', '=', '_'];
                    return str_replace($simbolos_invalidos, '', $string);
                }, $fila);
                $fila = array_map('trim', $fila);

                // // Cancelar si hay estudiante sin documento
                // if( !isset($fila['C']) || $fila['C'] == '' ) {
                //     $data['message'] = 'Uno o más estudiantes no tienen documento, por favor indique DNI, CE o código de estudiante';
                //     $resultados = [];
                //     exit;
                // }

                // Ignorar filas sin apellido y nombres
                if ((!isset($fila['D'])) || (!isset($fila['F']))) {
                    continue;
                } else {
                    if (($fila['D'] == '') && ($fila['F'] == '')) {
                        continue;
                    }
                }

                // Formatear resultados de estudiantes en nuevo array
                $fecha = isset($fila['B']) ? Date::excelToDateTimeObject($fila['B']) : null;
                $sexo = isset($fila['I']) ? ($sexos[$fila['I']] ?? null) : null;
                $resultados[] = array(
                    // 'fecha' => Carbon::createFromFormat('d/m/Y', $fila['B'])->format('Y-m-d'),
                    'fecha' => isset($fecha) ? $fecha->format('Y-m-d') : null,
                    'documento' => isset($fila['C']) ? $fila['C'] : null,
                    'grado' => isset($fila['AK']) ? $fila['AK'] : null,
                    'paterno' => isset($fila['D']) ? $fila['D'] : null,
                    'materno' => isset($fila['E']) ? $fila['E'] : null,
                    'nombres' => isset($fila['F']) ? $fila['F'] : null,
                    'sexo' => isset($sexo) ? $sexo : null,
                    'cod_modular' => isset($fila['K']) ? $fila['K'] : null,
                    'seccion' => isset($fila['P']) ? $fila['P'] : null,
                    'respuesta01' => isset($fila['Q']) ? $fila['Q'] : null,
                    'respuesta02' => isset($fila['R']) ? $fila['R'] : null,
                    'respuesta03' => isset($fila['S']) ? $fila['S'] : null,
                    'respuesta04' => isset($fila['T']) ? $fila['T'] : null,
                    'respuesta05' => isset($fila['U']) ? $fila['U'] : null,
                    'respuesta06' => isset($fila['V']) ? $fila['V'] : null,
                    'respuesta07' => isset($fila['W']) ? $fila['W'] : null,
                    'respuesta08' => isset($fila['X']) ? $fila['X'] : null,
                    'respuesta09' => isset($fila['Y']) ? $fila['Y'] : null,
                    'respuesta10' => isset($fila['Z']) ? $fila['Z'] : null,
                    'respuesta11' => isset($fila['AA']) ? $fila['AA'] : null,
                    'respuesta12' => isset($fila['AB']) ? $fila['AB'] : null,
                    'respuesta13' => isset($fila['AC']) ? $fila['AC'] : null,
                    'respuesta14' => isset($fila['AD']) ? $fila['AD'] : null,
                    'respuesta15' => isset($fila['AE']) ? $fila['AE'] : null,
                    'respuesta16' => isset($fila['AF']) ? $fila['AF'] : null,
                    'respuesta17' => isset($fila['AG']) ? $fila['AG'] : null,
                    'respuesta18' => isset($fila['AH']) ? $fila['AH'] : null,
                    'respuesta19' => isset($fila['AI']) ? $fila['AI'] : null,
                    'respuesta20' => isset($fila['AJ']) ? $fila['AJ'] : null,
                    'documento_docente' => isset($fila['AN']) ? $fila['AN'] : null,
                );
            }
        }

        $data['resultados'] = $resultados;

        return $data;
    }

    private function subirArchivo($request)
    {
        if ($request->has('archivo')) {
            try {
                $archivo = $request->file('archivo');
                $nombreArchivo = str_replace('.', '', $request->cCursoNombre . '-' . $request->cGradoAbreviacion);
                $rutaDestino = 'resultados/' . $request->iSedeId . '/';

                // if (!Storage::disk('public')->exists($rutaDestino)) {
                //     Storage::disk('public')->makeDirectory($rutaDestino);
                // }
                Storage::disk('public')->put($rutaDestino . $nombreArchivo, $archivo);
            } catch (Exception $e) {
                return false;
            }
        }

        if (Storage::disk('public')->exists($rutaDestino . $nombreArchivo)) {
            return true;
        } else {
            return false;
        }
    }
}
