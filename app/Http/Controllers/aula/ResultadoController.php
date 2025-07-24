<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Dompdf\Options;
use App\Helpers\VerifyHash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class ResultadoController extends Controller
{
    //private apiUrl = 'http://localhost:8000/api'; // Backend URL
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function obtenerResultados(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idDocCursoId' => ['required'],
            'iEstudianteId' => ['required'],
        ], [
            'idDocCursoId.required' => 'No se encontró el identificador del curso',
            'iEstudianteId.required' => 'No se encontró el identificador del estudiante',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $fieldsToDecode = [
            'idDocCursoId',
            'iEstudianteId',
        ];
        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

        try {
            $params = [
                $request->idDocCursoId      ??  NULL,
                $request->iEstudianteId     ??  NULL
            ];


            $data = DB::select('EXEC aula.Sp_SEL_obtenerResultadosxidDocCursoIdxiEstudianteId ?,?', $params);

            $foro = json_decode($data[0]->foro, true);
            $tarea = json_decode($data[0]->tarea, true);
            $evaluacion = json_decode($data[0]->evaluacion, true);

            $mapEncrypted = fn($item) => [
                ...$item,
                'iProgActId'         => VerifyHash::encodexId($item['iProgActId'] ?? null),
                'iContenidoSemId'    => VerifyHash::encodexId($item['iContenidoSemId'] ?? null),
                'iPeriodoEvalAperId' => VerifyHash::encodexId($item['iPeriodoEvalAperId'] ?? null),
            ];

            $foro = collect($foro)->map($mapEncrypted);
            $tarea = collect($tarea)->map($mapEncrypted);
            $evaluacion = collect($evaluacion)->map($mapEncrypted);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data'      => [
                'foro'        => $foro,
                'tarea'       => $tarea,
                'evaluacion'  => $evaluacion,
            ]];
            $estado = 200;

            return $response;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }
    public function guardarCalfcEstudiante(Request $request)
    {
        //$esquema='grl';
        // Validar los datos recibidos
        $request->validate([
            'esquema' => 'required|string',
            'tabla' => 'required|string',
            'datos' => 'required|array',
            'where' => 'required|array',
        ]);

        $esquema = $request->input('esquema');
        $tabla = $request->input('tabla');
        $datos = $request->input('datos'); // Datos que se actualizarán
        $where = $request->input('where'); // Condición WHERE opcional

        // Convertir datos a JSON si se requiere por el procedimiento almacenado
        $datosJson = json_encode($datos);
        $whereJson = json_encode($where);
        try {
            // Ejecutar el procedimiento almacenado usando DB::select
            $results = DB::select(
                'EXEC grl.SP_UPD_EnTablaConJSON
                    @Esquema = ?, @Tabla = ?,@DatosJSON = ?, @CondicionesJSON = ?',
                [$esquema, $tabla, $datosJson, $whereJson]
            );

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $results];
            $estado = 200;

            return $response;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $estado = 500;
        }
        return new JsonResponse($response, $estado);
    }
    public function obtenerCalificacionesFinalesReporte(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'tabla' => 'required|string',
            'campos' => 'nullable|string',
            'where' => 'nullable|string'
        ]);
        $esquema = 'acad';
        $tabla = $request->input('tabla');
        $campos = $request->input('campos', '*'); // Usar todos los campos si no se especifica
        $where = $request->input('where', '1=1'); // Condición por defecto si no se proporciona
        //$where = addslashes($request->input('where', '1=1'));
        try {
            // Ejecutar el procedimiento almacenado usando DB::select
            $data = DB::select(
                'EXEC grl.SP_SEL_DesdeTablaOVista
                        @nombreEsquema = ?, @nombreObjeto = ?,@campos = ?, @condicionWhere=?',
                [$esquema, $tabla, $campos, $where]
            );

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = 200;

            return $response;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $estado = 500;
        }
        return new JsonResponse($response, $estado);
    }
    public function habilitarCalificacion(Request $request)
    {
        //return 1;
        try {
            $data = DB::select('EXEC acad.SP_SEL_obtenerPeriodosEvaluacion ?,?', [$request->iYAcadId, $request->iCredId]);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = 200;

            return $response;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }
    public function obtenerReporteFinalNotas(Request $request)
    {
        // Validación de los parámetros de entrada
        $validator = Validator::make($request->all(), [
            'iIeCursoId' => ['required'],
            'iYAcadId' => ['required'],
            'iSedeId' => ['required'],
            'iSeccionId' => ['required'],
            'iNivelGradoId' => ['required'],
            'iEstudianteId' => ['required'],
        ], [
            'iIeCursoId.required' => 'No se encontró el identificador iIeCursoId',
            'iYAcadId.required' => 'No se encontró el identificador iYAcadId',
            'iSedeId.required' => 'No se encontró el identificador iSedeId',
            'iSeccionId.required' => 'No se encontró el identificador iSeccionId',
            'iNivelGradoId.required' => 'No se encontró el identificador iNivelGradoId',
            'iEstudianteId.required' => 'No se encontró el identificador iEstudianteId'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validated' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $fieldsToDecode = [
            'iIeCursoId',
            'iYAcadId',
            'iSedeId',
            'iSeccionId',
            'iNivelGradoId',
            'iEstudianteId'
        ];

        $request =  VerifyHash::validateRequest($request, $fieldsToDecode);

        $parametros = [
            $request->iIeCursoId            ??      NULL,
            $request->iYAcadId              ??      NULL,
            $request->iSedeId               ??      NULL,
            $request->iSeccionId            ??      NULL,
            $request->iNivelGradoId         ??      NULL,
            $request->iEstudianteId         ??      NULL,
        ];

        try {
            // Ejecutar el procedimiento almacenado

            $data = DB::select(
                'EXEC [acad].[Sp_SEL_reporteFinalDeNotas] 
                    @_iIeCursoId=?,
                    @_iYAcadId=?,
                    @_iSedeId=?,
                    @_iSeccionId=?,
                    @_iNivelGradoId=?,
                    @_iEstudianteId=?',
                $parametros
            );
            // Preparar la respuesta
            $fieldsToDecode = [
                'iEscalaCalifIdPromedio',
                'iEscalaCalifIdPeriodo1',
                'iEscalaCalifIdPeriodo2',
                'iEscalaCalifIdPeriodo3',
                'iEscalaCalifIdPeriodo4',
            ];
            $data = VerifyHash::encodeRequest($data, $fieldsToDecode);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = Response::HTTP_OK;

            return $response;
        } catch (\Exception $e) {
            // Manejo de excepción y respuesta de error
            $response = [
                'validated' => false,
                'message' => $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine(),
                'data' => [],
            ];
            $estado = Response::HTTP_INTERNAL_SERVER_ERROR;
            return new JsonResponse($response, $estado);
        }
    }
    //Para imprimir el reporte de logros alcanzados por trimestre
    public function reporteDeLogros(Request $request)
    {
        // Validación de los parámetros de entrada
        $request->validate([
            'iIeCursoId' => 'required|string ',
            'idDocCursoId' => 'required|string ',
        ]);
        //return $request->iCursoId;

        $idDocCursoId = $request->idDocCursoId;
        if ($request->idDocCursoId) {
            $idDocCursoId = $this->hashids->decode($idDocCursoId);
            $idDocCursoId = count($idDocCursoId) > 0 ? $idDocCursoId[0] : $idDocCursoId;
        }
        $iCursoId = $request->iIeCursoId;
        // Si se pasa un valor para iCursoId, decodificarlo
        if ($request->iIeCursoId) {
            $iCursoId = $this->hashids->decode($iCursoId);
            $iCursoId = count($iCursoId) > 0 ? $iCursoId[0] : $iCursoId;
        }

        //$cPersNombreLargo = "Docente";
        //CARGAR LOGOS 
        $imagePath = public_path('images\logo_IE\dremo.jpg');
        $imageData = base64_encode(file_get_contents($imagePath));
        $region = 'data:image/jpeg;base64,' . $imageData;

        $imagePath = public_path('images\logo_IE\juan_XXIII.jpg');
        $imageData = base64_encode(file_get_contents($imagePath));
        $insignia = 'data:image/jpeg;base64,' . $imageData;
        //'data:image/jpeg;base64,' . $imageData;

        $imagePath = public_path('images\logo_IE\Logo-buho.jpg');
        $imageData = base64_encode(file_get_contents($imagePath));
        $virtual = 'data:image/jpeg;base64,' . $imageData;

        // $data_curso = DB:: select('EXEC aula.SP_SEL_listarDatosXidDocCursoId',[$idDocente]);
        // return $data_curso;
        $data_header = DB::select('EXEC aula.SP_SEL_listarDatosXidDocCursoId ?', [$idDocCursoId]);
        $datos1 = [];
        foreach ($data_header as $header) {
            $datos1 = [
                'cod_Mod' => $header->cIieeCodigoModular,
                'docente' => $header->docente,
                'año' => $header->cYAcadNombre,
                'nivel_educativo' => $header->iNivelId,
                'Seccion_turno' => $header->cSeccionNombre,
                'ciclo_grado' => $header->cGradoNombre,
                'curso' => $header->cCursoNombre,
            ];
        }

        //return $datos1;
        $data = DB::select('EXEC acad.Sp_SEL_reporteFinalDeNotas ?', [$iCursoId]);
        $datos = [];
        foreach ($data as $key => $pregunta) {

            // Si pasa los filtros, agregar la pregunta a los datos
            $datos['data'][$key] = [

                'completoalumno' => $pregunta->completoalumno,
                'Trimestre_I' => $pregunta->iEscalaCalifIdPeriodo1,
                'Trimestre_II' => $pregunta->iEscalaCalifIdPeriodo2,
                'Trimestre_III' => $pregunta->iEscalaCalifIdPeriodo3,
                'Trimestre_IV' => $pregunta->iEscalaCalifIdPeriodo4,
                'Conclusion_descriptiva' => $pregunta->cDetMatConclusionDescPromedio,
            ];
        }
        $data = [
            'headers' => $datos1,
            'preguntas' => $datos['data'],
            "imageLogo" => $region, // Ruta absoluta
            "logoVirtual" => $virtual, // Ruta absoluta
            "logoInsignia" => $insignia, // Ruta absoluta
            "cPersNombreLargo" => $pregunta->completoalumno,
            "imageLogo" => $region,
        ];

        $pdf = PDF::loadView('aula.nivelDeLogrosReporte', $data)
            ->setPaper('a4', 'landscape')
            ->stream('reporteLogro.pdf');

        return $pdf;
    }
    //para imprimir el reporte de logros alcanzados durante el año
    public function reporteDeLogroFinalXYear(Request $request)
    {
        // @iSedeId INT,
        // @iSeccionId INT,
        // @iYAcadId INT,
        // @iNivelGradoId INT
        $iSedeId = $request->iSedeId;
        $iSeccionId = 2;
        $iYAcadId = 3;
        $iNivelGradoId = 9;
        $params = [
            $iSedeId,
            $iSeccionId,
            $iYAcadId,
            $iNivelGradoId
        ];

        try {
            // Ejecutar el procedimiento almacenado
            $data = DB::select('EXEC [aula].[SP_SEL_listarEstudiantesSedeSeccionYAcad] ?,?,?,?', $params);
            // Preparar la respuesta
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = 200;

            return $response;
        } catch (\Exception $e) {
            // Manejo de excepción y respuesta de error
            $response = [
                'validated' => false,
                'message' => $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine(),
                'data' => [],
            ];
            $estado = 500;
            return new JsonResponse($response, $estado);
        }
    }
    //para descargar el reporte final de logros alcanzados durante el año
    public function generarReporteDeLogrosAlcanzadosXYear(Request $request)
    {
        //return $request ->all();
        // Decodificar el JSON a un array asociativo

        $datosEstudiante = $request->datosEstudiante;
        $datosCursoEstudiante = $request->datosCursoEstudiante;

        $datosArray = json_decode($datosEstudiante, true);
        $datosArray01 = json_decode($datosCursoEstudiante, true);

        $idDocCursoId = 1;

        //CARGAR LOGOS 
        $imagePath = public_path('images\logo_IE\dremo.jpg');
        $imageData = base64_encode(file_get_contents($imagePath));
        $region = 'data:image/jpeg;base64,' . $imageData;

        $imagePath = public_path('images\logo_IE\juan_XXIII.jpg');
        $imageData = base64_encode(file_get_contents($imagePath));
        $insignia = 'data:image/jpeg;base64,' . $imageData;
        //'data:image/jpeg;base64,' . $imageData;

        $imagePath = public_path('images\logo_IE\Logo-buho.jpg');
        $imageData = base64_encode(file_get_contents($imagePath));
        $virtual = 'data:image/jpeg;base64,' . $imageData;
        //obtener la cabecera de reporte de logros
        $data_header = DB::select('EXEC aula.SP_SEL_listarDatosXidDocCursoId ?', [$idDocCursoId]);
        $datos1 = [];
        foreach ($data_header as $header) {
            $datos1 = [
                'cod_Mod' => $header->cIieeCodigoModular,
                'docente' => $header->docente,
                'año' => $header->cYAcadNombre,
                'nivel_educativo' => $header->iNivelId,
                'Seccion_turno' => $header->cSeccionNombre,
                'ciclo_grado' => $header->cGradoNombre,
                'curso' => $header->cCursoNombre,
            ];
        }

        //$data = DB::select('EXEC [aula].[SP_SEL_listarEstudiantesSedeSeccionYAcad] ?,?,?,?', $params);
        $data = [
            'headers' => $datos1,
            //'preguntas' => $datos['data'],
            "imageLogo" => $region, // Ruta absoluta
            "logoVirtual" => $virtual, // Ruta absoluta
            "logoInsignia" => $insignia, // Ruta absoluta
            "estudiante" => $datosArray,
            "imageLogo" => $region,
            "cursos" => $datosArray01,
        ];
        //return $data;

        $pdf = PDF::loadView('aula.nivelDeLogrosXYearReporte', $data)
            ->setPaper('a4', 'landscape')
            ->stream('reporteLogro.pdf');

        return $pdf;
    }
}
