<?php

namespace App\Http\Requests\bienestar;

use Illuminate\Foundation\Http\FormRequest;

class FichaViviendaSaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'iSesionId' => 'required|integer',
            'iViendaCarId' => 'nullable|integer',
            'iFichaDGId' => 'nullable|integer',
            'iTipoOcupaVivId' => 'nullable|integer',
            'iMatPreId' => 'nullable|integer',
            'iTipoVivId' => 'nullable|integer',
            'iViviendaCarNroPisos' => 'nullable|integer',
            'iViviendaCarNroAmbientes' => 'nullable|integer',
            'iViviendaCarNroHabitaciones' => 'nullable|integer',
            'iEstadoVivId' => 'nullable|integer',
            'iMatPisoVivId' => 'nullable|integer',
            'iMatTecVivId' => 'nullable|integer',
            'iTiposSsHhId' => 'nullable|integer',
            'iTipoSumAId' => 'nullable|integer',
            'iTipoAlumId.*' => 'nullable|integer',
            'iEleParaVivId.*' => 'nullable|integer',
        ];
    }

    public function attributes(): array
    {
        return [
            'iSesionId' => 'id de sesión',
            'iViendaCarId' => 'ficha de vivienda',
            'iFichaDGId' => 'id de ficha',
            'iTipoOcupaVivId' => 'tipo de ocupación',
            'iMatPreId' => 'material de paredes',
            'iTipoVivId' => 'tipo de vivienda',
            'iViviendaCarNroPisos' => 'número de pisos',
            'iViviendaCarNroAmbientes' => 'número de ambientes',
            'iViviendaCarNroHabitaciones' => 'número de dormitorios',
            'iEstadoVivId' => 'estado de vivienda',
            'iMatPisoVivId' => 'material de pisos',
            'iMatTecVivId' => 'material de techos',
            'iTiposSsHhId' => 'tipo de sshh',
            'iTipoSumAId' => 'suministro de agua',
            'iTipoAlumId.*' => 'tipo de alumbrado',
            'iEleParaVivId.*' => 'otros elementos',
        ];
    }
}
