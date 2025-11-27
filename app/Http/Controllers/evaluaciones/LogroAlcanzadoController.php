<?php

namespace App\Http\Controllers\evaluaciones;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\eval\LogroAlcanzado;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LogroAlcanzadoController extends Controller
{
    private $permitidos = [
        Perfil::DOCENTE,
    ];

    public function obtenerPeriodosEvaluacionSede(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->permitidos]);
            $data = LogroAlcanzado::selPeriodosEvaluacionSede($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function obtenerDatosCursoDocente(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->permitidos]);
            $data = LogroAlcanzado::selDatosCursoDocente($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function obtenerLogrosEstudiante(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->permitidos]);
            $data = LogroAlcanzado::selLogrosAlcanzadosEstudiante($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarLogro(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->permitidos]);
            $data = LogroAlcanzado::actualizarLogro($request);
            return FormatearMensajeHelper::ok('Se guardó el dato', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function obtenerEscalasCalificacion(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->permitidos]);
            $data = LogroAlcanzado::selEscalasCalificacionCurso($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarEscalaCalificacion(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->permitidos]);
            $data = LogroAlcanzado::updEscalaCalificacionCurso($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function exportarBoletas(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->permitidos]);
            $data = LogroAlcanzado::selLogrosAlcanzadosMasivo($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function exportarExcel(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->permitidos]);
            $data = LogroAlcanzado::selLogrosAlcanzadosMasivo($request);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }

        $filtros = $data[0][0];
        $competencias = $data[1];
        $periodos = $data[2];
        $notas = $data[3];

        foreach ( $notas as $nota) {
            $nota->competencias = json_decode($nota->competencias);
        }

        return view('eval.registro_notas_excel', compact('filtros', 'competencias', 'periodos', 'notas'));
    }

    public function exportarFormatoSiagie(Request $request)
    {
        try {
            Gate::authorize('tiene-perfil', [$this->permitidos]);
            $data = LogroAlcanzado::selLogrosAlcanzadosPeriodo($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        }
        catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}