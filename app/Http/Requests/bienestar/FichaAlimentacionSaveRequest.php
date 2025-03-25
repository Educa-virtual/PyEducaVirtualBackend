<?php

namespace App\Http\Requests\bienestar;

use Illuminate\Foundation\Http\FormRequest;

class FichaAlimentacionSaveRequest extends FormRequest
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
            'iLugarAlimIdDesayuno' => 'nullable|integer',
            'cLugarAlimDesayuno' => 'nullable|string',
            'iLugarAlimIdAlmuerzo' => 'nullable|integer',
            'cLugarAlimAlmuerzo' => 'nullable|string',
            'iLugarAlimIdCena' => 'nullable|integer',
            'cLugarAlimCena' => 'nullable|string',
            'iProAlimId.*' => 'nullable|integer',
            'cProAlimNombre' => 'nullable|string',
            'bDietaVegetariana' => 'nullable|boolean',
            'cDietaVegetarianaObs' => 'nullable|string',
            'bDietaVegana' => 'nullable|boolean',
            'cDietaVeganaObs' => 'nullable|string',
            'bAlergiasAlim' => 'nullable|boolean',
            'cAlergiasAlimObs' => 'nullable|string',
            'bIntoleranciaAlim' => 'nullable|boolean',
            'cIntoleranciaAlimObs' => 'nullable|string',
            'bSumplementosAlim' => 'nullable|boolean',
            'cSumplementosAlimObs' => 'nullable|string',
            'bDificultadAlim' => 'nullable|boolean',
            'cDificultadAlimObs' => 'nullable|string',
            'cInfoAdicionalAlimObs' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'iSesionId' => 'id de sesión',
            'iFichaDGId' => 'id de ficha',
            'iLugarAlimIdDesayuno' => 'lugar de desayuno',
            'cLugarAlimDesayuno' => 'otro lugar de desayuno',
            'iLugarAlimIdAlmuerzo' => 'lugar de almuerzo',
            'cLugarAlimAlmuerzo' => 'otro lugar de almuerzo',
            'iLugarAlimIdCena' => 'lugar de cena',
            'cLugarAlimCena' => 'otro lugar de cena',
            'iProAlimId.*' => 'programas de alimentacion',
            'cProAlimNombre' => 'otro programa de alimentacion',
            'bDietaVegetariana' => 'diea vegetariana',
            'cDietaVegetarianaObs' => 'observaciones de dieta vegetariana',
            'bDietaVegana' => 'dieta vegana',
            'cDietaVeganaObs' => 'observaciones de dieta vegana',
            'bAlergiasAlim' => 'alergias',
            'cAlergiasAlimObs' => 'otras alergias',
            'bIntoleranciaAlim' => 'intelorencias',
            'cIntoleranciaAlimObs' => 'otras intolerancias',
            'bSumplementosAlim' => 'suplementos',
            'cSumplementosAlimObs' => 'otros sumplementos',
            'bDificultadAlim' => 'dificultades',
            'cDificultadAlimObs' => 'otras dificultades',
            'cInfoAdicionalAlimObs' => 'informacion adicional',
        ];
    }
}
