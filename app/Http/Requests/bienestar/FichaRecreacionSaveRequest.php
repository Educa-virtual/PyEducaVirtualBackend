<?php

namespace App\Http\Requests\bienestar;

use Illuminate\Foundation\Http\FormRequest;

class FichaRecreacionSaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'iSesionId' => 'required|integer',
            'iFichaDGId' => 'nullable|integer',
            'cFichaDGPerteneceLigaDeportiva' => 'nullable|string',
            'iDeporteId' => 'nullable|integer',
            'cDepFichaObs' => 'nullable|string',
            'cFichaDGPerteneceCentroArtistico' => 'nullable|string|max:18',
            'iPasaTiempoId' => 'nullable|integer',
            'cPasaTFichaHoras' => 'nullable|integer',
            'cFichaDGAsistioConsultaPsicologica' => 'nullable|string|max:18',
            'cProbEFichaPresentePara' => 'nullable|string',
            'iTipoFamiliarId' => 'nullable|integer',
            'iTransporteId' => 'nullable|integer',
            'nTransFichaGastoSoles' => 'nullable|decimal',
        ];
    }

    public function attributes(): array
    {
        return [
            'iSesionId' => 'id de sesión',
            'iFichaDGId' => 'id de ficha',
            'cFichaDGPerteneceLigaDeportiva' => 'pertenece a liga deportiva',
            'iDeporteId' => 'derporte',
            'cDepFichaObs' => 'observacion de deporte',
            'cFichaDGPerteneceCentroArtistico' => 'pertenece a centro artístico',
            'iPasaTiempoId' => 'pastiempo',
            'cPasaTFichaHoras' => 'horas de pasatiempo',
            'cFichaDGAsistioConsultaPsicologica' => 'asistió a consulta psicológica',
            'cProbEFichaPresentePara' => 'problema emocional',
            'iTipoFamiliarId' => 'familiar que aconseja',
            'iTransporteId' => 'transporte',
            'nTransFichaGastoSoles' => 'gastos de transporte',
        ];
    }
}
