<?php

namespace App\Http\Requests\acad;

use App\Helpers\VerifyHash;
use App\Http\Requests\GeneralFormRequest;

class ActualizarCalendarioAcademicosRequest extends GeneralFormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'iCalAcadId' => $this->route('iCalAcadId') ?? $this->input('iCalAcadId'),
        ]);

        $fieldsToDecode = [
            'iCalAcadId',
        ];
        $request_decodificado = VerifyHash::validateRequest($this, $fieldsToDecode);
        $this->merge([
            'iCalAcadId' => $request_decodificado->iCalAcadId,
        ]);
    }

    public function rules(): array
    {
        return [
            'iCalAcadId' => ['integer', 'required'],
            'dtCalAcadInicio' => ['date', 'required'],
            'dtCalAcadFin' => ['date', 'required', 'after:dtCalAcadInicio'],
            'iPeriodoEvalId' => ['integer', 'required'],
            'dtCalAcadMatriculaInicio' => ['date', 'required'],
            'dtCalAcadMatriculaFin' => ['date', 'required', 'after:dtCalAcadMatriculaInicio'],
            'dtCalAcadMatriculaResagados' => ['date', 'required', 'after:dtCalAcadMatriculaFin'],
            'dtFaseInicioRegular' => ['date', 'nullable'],
            'dtFaseFinRegular' => ['date', 'nullable', 'after: dtFaseInicioRegular'],
            'dtFaseInicioRecuperacion' => ['date', 'nullable', 'after_or_equal: dtFaseFinRegular'],
            'dtFaseFinRecuperacion' => ['date', 'nullable', 'after: dtFaseInicioRecuperacion'],
            'iTurnoId' => ['integer', 'nullable'],
            'dtAperTurnoInicio' => ['time', 'nullable', 'date_format:H:i'],
            'dtAperTurnoFin' => ['time', 'nullable', 'date_format:H:i', 'after:dtAperTurnoInicio'],
            'jsonDiasLaborables' => ['string', 'nullable', function ($attribute, $value, $fail) {
                $data = json_decode($value, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $fail('Ocurrió un error al leer los datos.');
                    return;
                }
                if (!is_array($data) || count($data) === 0) {
                    $fail('Debe indicar al menos un día laborable.');
                    return;
                }
            }],
        ];
    }

    public function attributes(): array
    {
        return [
            'iCalAcadId' => 'calendario académico',
            'dtCalAcadInicio' => 'inicio de año académico',
            'dtCalAcadFin' => 'cierre de año académico',
            'iPeriodoEvalId' => 'periodo de evaluación',
            'dtCalAcadMatriculaInicio' => 'inicio de matrículas',
            'dtCalAcadMatriculaFin' => 'cierre de matrículas',
            'dtCalAcadMatriculaResagados' => 'cierre de matrículas rezagadas',
            'dtFaseInicioRegular' => 'inicio de fase regular',
            'dtFaseFinRegular' => 'cierre de fase regular',
            'dtFaseInicioRecuperacion' => 'inicio de fase de recuperación',
            'dtFaseFinRecuperacion' => 'cierre de fase de recuperación',
            'iTurnoId' => 'turno',
            'dtAperTurnoInicio' => 'hora de inicio del turno',
            'dtAperTurnoFin' => 'hora de fin del turno',
            'jsonDiasLaborables' => 'días laborables',
        ];
    }
}
