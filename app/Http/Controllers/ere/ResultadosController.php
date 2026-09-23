<?php

namespace App\Http\Controllers\ere;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ere\GuardarHojaDesarrolloEstudianteRequest;
use App\Http\Requests\Ere\HojaDesarrolloEstudianteRequest;
use App\Services\Ere\ResultadosService;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ResultadosController extends Controller
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

    public function validateRequest(Request $request, $fieldsToDecode, $completo = true)
    {
        $request->validate(
            ['opcion' => 'required'],
            ['opcion.required' => 'Hubo un problema al obtener la acción']
        );

        foreach ($fieldsToDecode as $field) {
            $request[$field] = $this->decodeValue($request->$field);
        }

        return ! $completo ? $request : [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $request->idTipoEvalId ?? null,
            $request->iNivelEvalId ?? null,
            $request->dtEvaluacionCreacion ?? null,
            $request->cEvaluacionNombre ?? null,
            $request->cEvaluacionDescripcion ?? null,
            $request->cEvaluacionUrlDrive ?? null,
            $request->cEvaluacionUrlPlantilla ?? null,
            $request->cEvaluacionUrlManual ?? null,
            $request->cEvaluacionUrlMatriz ?? null,
            $request->cEvaluacionObs ?? null,
            $request->dtEvaluacionLiberarMatriz ?? null,
            $request->dtEvaluacionLiberarCuadernillo ?? null,
            $request->dtEvaluacionLiberarResultados ?? null,
            $request->iEstado ?? null,
            $request->iSesionId ?? null,
            $request->iEvaluacionId ?? null,
            $request->cEvaluacionIUrlCuadernillo ?? null,
            $request->cEvaluacionUrlHojaRespuestas ?? null,
            $request->dtEvaluacionFechaInicio ?? null,
            $request->dtEvaluacionFechaFin ?? null,

            $request->iCredId ?? null,
        ];
    }

    private function encodeFields($item)
    {
        $fieldsToEncode = [];

        foreach ($fieldsToEncode as $field) {
            if (isset($item->$field)) {
                $item->$field = $this->hashids->encode($item->$field);
            }
        }

        return $item;
    }

    public function encodeId($data)
    {
        return array_map([$this, 'encodeFields'], $data);
    }

    public function guardarResultadosxiEstudianteIdxiResultadoRptaEstudiante(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iResultadoId',
                'iEstudianteId',
                'iResultadoRptaEstudiante',
                'iIieeId',
                'iEvaluacionId',
                'iYAcadId',
                'iPreguntaId',
                'iCursoNivelGradId',
                'iMarcado',
            ];

            $request = $this->validateRequest($request, $fieldsToDecode, false);
            $parametros = [
                $request->iResultadoId ?? null,
                $request->iEstudianteId ?? null,
                $request->iResultadoRptaEstudiante ?? null,
                $request->iIieeId ?? null,
                $request->iEvaluacionId ?? null,
                $request->iYAcadId ?? null,
                $request->iPreguntaId ?? null,
                $request->iCursoNivelGradId ?? null,
                $request->iMarcado ?? null,
            ];
            $data = DB::select('exec ere.SP_INS_UPD_GuardaRptasEvaluacion ?,?,?,?,?,?,?,?,?', $parametros);
            if ($data[0]->iResultadoId > 0) {
                return FormatearMensajeHelper::ok('Se ha registrado su respuesta');
            } else {
                throw new Exception('Hubo un problema al registrar su respuesta, por favor intente nuevamente');
            }
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function terminarExamenxiEstudianteId(Request $request)
    {
        try {
            $fieldsToDecode = [
                'iEstudianteId',
                'iIieeId',
                'iEvaluacionId',
                'iYAcadId',
                'iCursoNivelGradId',
            ];

            $request = $this->validateRequest($request, $fieldsToDecode, false);
            $parametros = [
                $request->iEstudianteId ?? null,
                $request->iIieeId ?? null,
                $request->iEvaluacionId ?? null,
                $request->iYAcadId ?? null,
                $request->iCursoNivelGradId ?? null,
            ];
            $data = DB::select('exec ere.SP_UPD_terminarExamenxiEstudianteId ?,?,?,?,?', $parametros);

            return new JsonResponse(
                ['validated' => true, 'message' => 'Su examen ha finalizado', 'data' => $data],
                200
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []],
                500
            );
        }
    }

    public function descargarHojaDesarrolloEstudiante(HojaDesarrolloEstudianteRequest $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ESTUDIANTE, Perfil::DIRECTOR_IE]]);
            ob_end_clean(); // Sin esto, al descargar el archivo, se muestra un mensaje de error al abrirlo
            $ruta = ResultadosService::obtenerHojaDesarrolloEstudiante($request);
            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
            $disk = Storage::disk('public');

            return $disk->download($ruta);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function guardarHojaDesarrolloEstudiante(GuardarHojaDesarrolloEstudianteRequest $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ESTUDIANTE, Perfil::DIRECTOR_IE]]);
            ResultadosService::guardarHojaDesarrolloEstudiante($request);

            return FormatearMensajeHelper::ok('Se ha guardado el archivo del estudiante');
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }

    public function eliminarHojaDesarrolloEstudiante(HojaDesarrolloEstudianteRequest $request)
    {
        try {
            Gate::authorize('tiene-perfil', [[Perfil::ESTUDIANTE, Perfil::DIRECTOR_IE]]);
            ResultadosService::eliminarHojaDesarrolloEstudiante($request->iEvaluacionId, $request->iCursosNivelGradId, $request->iEstudianteId);

            return FormatearMensajeHelper::ok('Se ha eliminado el archivo del estudiante');
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
    }
}
