<?php

namespace App\Http\Requests\seg;

use App\Http\Requests\GeneralFormRequest;

class AuditoriaFiltroFechaRequest extends GeneralFormRequest
{
    public function rules(): array
    {
        return [
            'filtroFechaInicio' => 'required|date',
            'filtroFechaFin' => 'required|date|after_or_equal:filtroFechaInicio',
        ];
    }

    public function attributes(): array
    {
        return [
            'filtroFechaInicio' => 'Fecha de inicio',
            'filtroFechaFin' => 'Fecha fin',
        ];
    }
}
