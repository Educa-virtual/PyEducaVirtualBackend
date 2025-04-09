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

    public function importar(Request $request)
    {
        // Subir archivo para revisión, desactivar eventualmente
        $this->subirArchivo($request);

        $datos_hojas = $this->leerHojas($request);

        $datos_hoja = $this->formatearDatos($datos_hojas);

        $curso_nivel_grado = in_array($request->iCursosNivelGradId, ['undefined', 'null', null, '', false, 0]) ? null : $request->iCursosNivelGradId;
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
            json_encode($datos_hoja['resultados']),
        ];

        if( count($datos_hoja['resultados']) === 0 ) {
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

    private function leerHojas($request)
    {
        $data = [];

        // Validar que request tiene al menos un archivo
        if($request->allFiles()) {

            // Obtener data solo del primer archivo
            foreach( $request->file() as $file) {
                $archivo = $file;
                break;
            }

            if( !$archivo ) {
                return $data;
            }

            $spreadsheet = IOFactory::load(
                    $archivo, 
                    IReader::READ_DATA_ONLY | IReader::IGNORE_ROWS_WITH_NO_CELLS | IReader::IGNORE_EMPTY_CELLS,
                    [IOFactory::READER_XLSX]
                );

            /* CONSOLIDADO */
            $hoja = $spreadsheet->getSheet(3);
            $data[0] = $hoja->toArray(null, true, true, true);
            /* PARAMETROS */
            $hoja = $spreadsheet->getSheet(4);
            $data[1] = $hoja->toArray(null, true, true, true);
        }

        return $data;
    }

    private function formatearDatos($hojas)
    {
        if( count($hojas) == 0 ) {
            return [];
        }

        $resultados = [];
        $filas = $hojas[0];
        $config = $hojas[1];

        $data['codigo_modular'] = trim($config[10]['C']);
        $data['curso'] = trim($config[8]['H']);
        $data['nivel'] = trim($config[8]['C']);
        $data['grado'] = trim($config[8]['N']);

        // Reemplazar texto a códigos identificadores
        $sexos = [
            'Femenino' => 'F',
            'Masculino' => 'M',
            'F' => 'F',
            'M' => 'M',
            'FEMENINO' => 'F',
            'MASCULINO' => 'M',
        ];

        foreach($filas as $index_fila => $fila)
        {
            // Extraer datos a partir de la fila 2
            if($index_fila > 1)
            {
                // Limpiar datos de la fila
                $fila = array_map('trim', $fila);

                // Ignorar filas sin apellido y nombres
                if (($fila['D'] == '') && ($fila['F'] == '')) {
                    continue;
                }

                // Formatear resultados de estudiantes en nuevo array
                $fecha = Date::excelToDateTimeObject($fila['B']);
                $resultados[] = array(
                    // 'fecha' => Carbon::createFromFormat('d/m/Y', $fila['B'])->format('Y-m-d'),
                    'fecha' => $fecha->format('Y-m-d'),
                    'documento' => $fila['C'],
                    'grado' => $fila['AK'],
                    'paterno' => strtoupper($fila['D']),
                    'materno' => strtoupper($fila['E']),
                    'nombres' => strtoupper($fila['F']),
                    'sexo' => $sexos[$fila['I']],
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
                    'documento_docente' => $fila['AN'],
                );
            }
        }

        $data['resultados'] = $resultados;
        
        return $data;
    }

    private function subirArchivo($request)
    {
        if( $request->has('archivo') ) {
            try {
                $archivo = $request->file('archivo');
                $nombreArchivo = str_replace('.', '', $request->cCursoNombre . '-' . $request->cGradoAbreviacion);
                $rutaDestino = 'resultados/'. $request->iSedeId . '/';

                // if (!Storage::disk('public')->exists($rutaDestino)) {
                //     Storage::disk('public')->makeDirectory($rutaDestino);
                // }
                Storage::disk('public')->put($rutaDestino.$nombreArchivo, $archivo);
            } catch (Exception $e) {
                return false;
            }
        }

        if( Storage::disk('public')->exists($rutaDestino.$nombreArchivo) ) {
            return true;
        } else {
            return false;
        }
    }
}