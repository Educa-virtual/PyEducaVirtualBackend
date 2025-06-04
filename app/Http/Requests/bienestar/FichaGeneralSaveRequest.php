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
            'iPersId' => 'required|integer',
            'iTipoViaId' => 'nullable|integer',
            'cFichaDGDireccionNombreVia' => 'nullable|string|max:150',
            'cFichaDGDireccionNroPuerta' => 'nullable|string|max:10',
            'cFichaDGDireccionBlock' => 'nullable|string|max:3',
            'cFichaDGDirecionInterior' => 'nullable|string|max:3',
            'cFichaDGDirecionPiso' => 'nullable|integer',
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
            'iPersId' => 'id de persona',
            'iTipoViaId' => 'tipo de vía',
            'cFichaDGDireccionNombreVia' => 'nombre de vía',
            'cFichaDGDireccionNroPuerta' => 'número de puerta',
            'cFichaDGDireccionBlock' => 'block',
            'cFichaDGDirecionInterior' => 'interior',
            'cFichaDGDirecionPiso' => 'piso',
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
