<?php

namespace App\Http\Controllers\ere;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use App\Models\ere\Evaluacion;
use App\Repositories\acad\AreasRepository;
use App\Repositories\acad\DocentesRepository;
use App\Repositories\Acad\IeRepository;
use App\Repositories\ere\AreasRepository as EreAreasRepository;
use App\Repositories\ere\EvaluacionesRepository;
use App\Repositories\grl\PersonasRepository;
use App\Repositories\grl\YearsRepository;
use App\Repositories\PreguntasRepository;
use App\Services\ere\AreasService;
use App\Services\FechaHoraService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use ErrorException;
use Exception;
use Faker\Calculator\Ean;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Format;

class AreasController extends Controller
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function registrarHorasAreasPorEvaluacionDirectorIe($evaluacionId, $iieeId, $iPersId, Request $request)
    {
        Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);
        $evaluacionIdDescifrado = $this->hashids->decode($evaluacionId);
        $iPersIdDescifrado = $this->hashids->decode($iPersId);
        if (empty($evaluacionIdDescifrado) || empty($iPersIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }
        if (IeRepository::directorPerteneceIe($evaluacionIdDescifrado[0], $iieeId)) {
            return response()->json(['status' => 'Error', 'message' => 'El director no pertenece a la Institución educativa ingresada.'], Response::HTTP_BAD_REQUEST);
        }
        DB::beginTransaction();
        $iieeParticipaEval = DB::selectOne("SELECT * FROM ere.iiee_participa_evaluaciones WHERE iIieeId=? AND iEvaluacionId=?", [$iieeId, $evaluacionIdDescifrado[0]]);
        try {
            //Es mas rapido eliminar que verificar que existe y actualizar, o registrar, o eliminar si las horas estan vacias
            IeRepository::eliminarHorasExamen($iieeParticipaEval->iIeeParticipaId);
            foreach ($request->formulario as $fila) {

                if ($fila['horaInicio'] != null && $fila['horaFin'] != null) {
                    if (FechaHoraService::fechaInicioEsMayorFechaFin($fila['horaInicio'], $fila['horaFin'])) {
                        throw new Exception('La hora de inicio no puede ser mayor a la hora de fin.');
                    }
                    $horaInicio = FechaHoraService::convertirFechaUtcEnHoraLocal($fila['horaInicio']);
                    $horaFin = FechaHoraService::convertirFechaUtcEnHoraLocal($fila['horaFin']);
                    AreasRepository::registrarHorasAreasPorEvaluacionIe($fila, $horaInicio, $horaFin);
                }
            }
            DB::commit();
            return response()->json(['status' => 'Success', 'message' => 'Se han registrado las horas ingresadas'], Response::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['status' => 'Error', 'message' => $ex->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function obtenerHorasAreasPorEvaluacionDirectorIe($evaluacionId, $iieeId, $iPersId)
    {
        Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);
        $evaluacionIdDescifrado = $this->hashids->decode($evaluacionId);
        $iPersIdDescifrado = $this->hashids->decode($iPersId);
        if (empty($evaluacionIdDescifrado) || empty($iPersIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }
        $resultado = AreasRepository::obtenerHorasAreasPorEvaluacionIe($evaluacionIdDescifrado[0], $iieeId);
        return response()->json(['status' => 'Success', 'message' => 'Se obtuvo la información', 'data' => $resultado], Response::HTTP_OK);
    }

    public function guardarArchivoPdf($evaluacionId, $areaId, Request $request)
    {
        Gate::authorize('tiene-perfil', [[Perfil::ESPECIALISTA_DREMO]]);
        $evaluacionIdDescifrado = $this->hashids->decode($evaluacionId);
        $areaIdDescifrado = $this->hashids->decode($areaId);
        if (empty($evaluacionIdDescifrado) || empty($areaIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }
        $evaluacion = EvaluacionesRepository::obtenerEvaluacionPorId($evaluacionIdDescifrado[0]);
        if ($evaluacion == null) {
            return response()->json(['status' => 'Error', 'message' => 'No existe la evaluación con el ID enviado.'], Response::HTTP_NOT_FOUND);
        }
        $area = AreasRepository::obtenerAreaPorNivelGradId($areaIdDescifrado[0]);
        if ($area == null) {
            return response()->json(['status' => 'Error', 'message' => 'No existe el área con el ID enviado.'], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'archivo' => 'required|file|mimes:pdf|max:51200', // máximo 50MB
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return response()->json(['status' => 'Error', 'message' => $error[0]], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {

            AreasService::guardarArchivoErePdf($request, $evaluacionId, $areaId);
            return response()->json(['status' => 'Success', 'message' => 'Archivo guardado correctamente.'], Response::HTTP_OK);
        }
    }

    private function descargarArchivoPreguntasPdf($evaluacion, $area)
    {
        Gate::authorize('tiene-perfil', [[Perfil::ESPECIALISTA_DREMO, Perfil::ADMINISTRADOR_DREMO, Perfil::DIRECTOR_IE, Perfil::DOCENTE]]);
        try {
            $data = AreasService::obtenerArchivoErePdf($evaluacion, $area);
        } catch (Exception $ex) {
            abort(Response::HTTP_NOT_FOUND);
        }
        return response($data['contenido'], 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $data['nombreArchivo'] . '"'
        ]);
    }

    private function descargarArchivoPreguntasWord($evaluacion, $area)
    {
        //Gate::authorize('tiene-perfil', [[Perfil::ESPECIALISTA_DREMO]]); REACTIVAR
        $url = env('APP_ASPNET_URL') . "/api/ere/evaluaciones/$evaluacion->evaluacionIdHashed/areas/$area->areaIdCifrado/archivo-preguntas";

        $response = Http::withOptions([
            'stream' => true,
            'timeout' => 360,
            'connect_timeout' => 360,
        ])->get($url);
        if ($response->failed()) {
            abort(404, 'Archivo no encontrado.');
        }
        $contenido = $response->body();
        $nombreArchivo = 'archivo.docx';
        $contentDisposition = $response->header('Content-Disposition');
        // Si el header existe, intentamos extraer el nombre del archivo
        if ($contentDisposition && preg_match('/filename\*?=(?:UTF-8\'\')?["\']?([^"\';]+)/i', $contentDisposition, $matches)) {
            $nombreArchivo = $matches[1];
        }
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => "attachment; filename=\"$nombreArchivo\"",
        ];
        return response($contenido, 200, $headers);
    }

    public function descargarArchivoPreguntas($evaluacionId, $areaId, Request $request)
    {

        $evaluacionIdDescifrado = $this->hashids->decode($evaluacionId);
        $areaIdDescifrado = $this->hashids->decode($areaId);
        if (empty($evaluacionIdDescifrado) || empty($areaIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }
        $evaluacion = EvaluacionesRepository::obtenerEvaluacionPorId($evaluacionIdDescifrado[0]);
        if ($evaluacion == null) {
            return response()->json(['status' => 'Error', 'message' => 'No existe la evaluación con el ID enviado.'], Response::HTTP_NOT_FOUND);
        }
        $area = AreasRepository::obtenerAreaPorNivelGradId($areaIdDescifrado[0]);
        if ($area == null) {
            return response()->json(['status' => 'Error', 'message' => 'No existe el área con el ID enviado.'], Response::HTTP_NOT_FOUND);
        }
        $evaluacion->evaluacionIdHashed = $evaluacionId;
        $area->areaIdCifrado = $areaId;
        switch ($request->query('tipo')) {
            case 'pdf':
                return $this->descargarArchivoPreguntasPdf($evaluacion, $area);
            case 'word':
                return $this->descargarArchivoPreguntasWord($evaluacion, $area);
            default:
                return response()->json(['status' => 'Error', 'message' => 'Tipo de archivo no soportado.'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function descargarCartillaRespuestas($evaluacionId, $areaId)
    {
        $evaluacionIdDescifrado = $this->hashids->decode($evaluacionId);
        $areaIdDescifrado = $this->hashids->decode($areaId);
        if (empty($evaluacionIdDescifrado) || empty($areaIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }
        $evaluacion = EvaluacionesRepository::obtenerEvaluacionPorId($evaluacionIdDescifrado[0]);
        if ($evaluacion == null) {
            return response()->json(['status' => 'Error', 'message' => 'No existe la evaluación con el ID enviado.'], Response::HTTP_NOT_FOUND);
        }
        $area = AreasRepository::obtenerAreaPorNivelGradId($areaIdDescifrado[0]);
        if ($area == null) {
            return response()->json(['status' => 'Error', 'message' => 'No existe el área con el ID enviado.'], Response::HTTP_NOT_FOUND);
        }
        $evaluacion->evaluacionIdHashed = $evaluacionId;
        $area->areaIdCifrado = $areaId;
        try {
            $data = AreasService::obtenerCartillaRespuestas($evaluacion, $area);
        } catch (Exception $ex) {
            abort(Response::HTTP_NOT_FOUND);
        }
        return response($data['contenido'], 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'attachment; filename="' . $data['nombreArchivo'] . '"' //"attachment; filename=\"$nombreArchivo\"",
        ]);
    }

    public function generarMatrizCompetencias($evaluacionId, $areaId)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ESPECIALISTA_DREMO, Perfil::ADMINISTRADOR_DREMO, Perfil::DIRECTOR_IE, Perfil::DOCENTE]]);
            $usuario = Auth::user();
            return AreasService::generarMatrizCompetencias($evaluacionId, $areaId, $usuario);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function actualizarLiberacionAreasPorEvaluacion($evaluacionId)
    {
        $evaluacionIdDescifrado = $this->hashids->decode($evaluacionId);
        if (empty($evaluacionIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }
        AreasRepository::liberarAreasPorEvaluacion($evaluacionIdDescifrado[0]);
        return response()->json(['status' => 'Success', 'message' => 'Se han liberado las áreas de la evaluación especificada.'], Response::HTTP_OK);
    }

    public function obtenerAreasPorEvaluacion($evaluacionId)
    {
        try {
            $evaluacionIdDescifrado =  VerifyHash::decodesxId($evaluacionId);//$this->hashids->decode($evaluacionId);
            //$personaIdDescifrado =  $this->hashids->decode($personaId);
            if (empty($evaluacionIdDescifrado)) {
                throw new Exception('El ID enviado no se pudo descifrar.');
            }
            $resultados = DB::select(
                'EXEC [ere].SP_SEL_AreasEvaluacionesEspecialista @iEvaluacionId=?, @iPersId=?, @iCredEntPerfId=?',
                [$evaluacionIdDescifrado, Auth::user()->iPersId, request()->header('icredentperfid')]
            );

            if (empty($resultados)) {
                throw new Exception('No se encontraron datos para los cursos asociados.');
            }

            foreach ($resultados as $fila) {
                /*$params = [
                    'iEvaluacionId' => $evaluacionIdDescifrado[0],
                    'iCursosNivelGradId' => $fila->iCursosNivelGradId,
                    'busqueda' => '',
                    'iTipoPregId' => 0,
                    'bPreguntaEstado' => 1,
                    'iPreguntaId' => NULL,
                    'ids' => NULL
                ];*/
                $fila->iCantidadMaximaPreguntas = Evaluacion::selCantidadMaxPreguntas($evaluacionIdDescifrado, $fila->iCursosNivelGradId) ?? 20;//EvaluacionesRepository::selCantidadMaxPreguntas($evaluacionIdDescifrado, $fila->iCursosNivelGradId);
                $fila->iCantidadPreguntas = PreguntasRepository::obtenerCantidadPreguntasPorEvaluacion($evaluacionIdDescifrado, $fila->iCursosNivelGradId);
                $fila->iEvaluacionId = $evaluacionIdDescifrado;
                $fila->iCursosNivelGradId = $this->hashids->encode($fila->iCursosNivelGradId);
                $fila->bTieneArchivo = AreasService::tieneArchivoErePdfSubido($evaluacionId, $fila->iCursosNivelGradId);
                $fila->iEvaluacionIdHashed = $evaluacionId;
            }
            return FormatearMensajeHelper::ok('Datos obtenidos', $resultados);
            //return response()->json(['status' => 'Success', 'message' => 'Datos obtenidos.', 'data' => $resultados], Response::HTTP_OK);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
            //return response()->json(['status' => 'Error', 'message' => $ex->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
