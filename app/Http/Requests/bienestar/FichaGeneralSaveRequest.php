<?php

namespace App\Http\Requests\bienestar;

use Illuminate\Foundation\Http\FormRequest;

class FichaGeneralSaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'iTipoViaId' => 'nullable|integer',
            'cFichaDGDireccionNombreVia' => 'nullable|string|max:150',
            'cFichaDGDireccionNroPuerta' => 'nullable|string|max:10',
            'cFichaDGDireccionBlock' => 'nullable|string|max:3',
            'cFichaDGDireccionInterior' => 'nullable|string|max:3',
            'iFichaDGDireccionPiso' => 'nullable|integer',
            'cFichaDGDireccionManzana' => 'nullable|string|max:10',
            'cFichaDGDireccionLote' => 'nullable|string|max:3',
            'cFichaDGDireccionKm' => 'nullable|string|max:10',
            'cFichaDGDireccionReferencia' => 'nullable|string',
            'iReligionId' => 'nullable|integer',
            'bFamiliarPadreVive' => 'nullable|boolean',
            'bFamiliarMadreVive' => 'nullable|boolean',
            'bFamiliarPadresVivenJuntos' => 'nullable|boolean',
            'bFichaDGTieneHijos' => 'nullable|boolean',
            'iFichaDGNroHijos' => 'nullable|integer',
            'cTipoViaOtro' => 'nullable|string',
            'cReligionOtro' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'iTipoViaId' => 'tipo de vía',
            'cFichaDGDireccionNombreVia' => 'nombre de vía',
            'cFichaDGDireccionNroPuerta' => 'número de puerta',
            'cFichaDGDireccionBlock' => 'block',
            'cFichaDGDireccionInterior' => 'interior',
            'iFichaDGDireccionPiso' => 'piso',
            'cFichaDGDireccionManzana' => 'manzana',
            'cFichaDGDireccionLote' => 'lote',
            'cFichaDGDireccionKm' => 'km',
            'cFichaDGDireccionReferencia' => 'referencia',
            'iReligionId' => 'religión',
            'bFamiliarPadreVive' => 'padre vive',
            'bFamiliarMadreVive' => 'madre vive',
            'bFamiliarPadresVivenJuntos' => 'padres viven juntos',
            'bFichaDGTieneHijos' => 'tiene hijos',
            'iFichaDGNroHijos' => 'número de hijos',
            'cTipoViaOtro' => 'otro tipo de vía',
            'cReligionOtro' => 'otra religión',
        ];
    }
}
