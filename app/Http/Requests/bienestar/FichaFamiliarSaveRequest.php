<?php

namespace App\Http\Requests\bienestar;

use Illuminate\Foundation\Http\FormRequest;

class FichaFamiliarSaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'iSesionId' => 'required|integer',
            'iFichaDGId' => 'required|integer',
            'iPersId' => 'nullable|integer',
            'iTipoFamiliarId' => 'required|integer',
            'bFamiliarVivoConEl' => 'required|boolean',
            'iTipoIdentId' => 'required|integer',
            'cPersDocumento' => 'nullable|string|min:8|max:15',
            'cPersNombre' => 'required|string|max:50',
            'cPersPaterno' => 'required|string|max:50',
            'cPersMaterno' => 'required|string|max:50',
            'dPersNacimiento' => 'nullable|date',
            'cPersSexo' => 'required|string|max:1',
            'iTipoEstCivId' => 'nullable|integer',
            'iNacionId' => 'nullable|integer',
            'iDptoId' => 'nullable|integer',
            'iPrvnId' => 'nullable|integer',
            'iDsttId' => 'nullable|integer',
            'cPersDomicilio' => 'nullable|string',
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
            'iOcupacionId' => 'nullable|integer',
            'iGradoInstId' => 'nullable|integer',
            'iTipoIeEstId' => 'nullable|integer',
        ];
    }

    public function attributes(): array
    {
        return [
            'iSesionId' => 'sesión',
            'iFichaDGId' => 'id de ficha',
            'iPersId' => 'id de persona',
            'iTipoFamiliarId' => 'tipo de familiar',
            'bFamiliarVivoConEl' => 'comparte residencia',
            'iTipoIdentId' => 'tipo de identificación',
            'cPersDocumento' => 'documento de identidad',
            'cPersNombre' => 'nombres',
            'cPersPaterno' => 'primer apellido',
            'cPersMaterno' => 'segundo apellido',
            'dPersNacimiento' => 'fecha de nacimiento',
            'cPersSexo' => 'sexo',
            'iTipoEstCivId' => 'estado civil',
            'iNacionId' => 'nacionalidad',
            'iDptoId' => 'departamento',
            'iPrvnId' => 'provincia',
            'iDsttId' => 'distrito',
            'cPersDomicilio' => 'domicilio',
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
            'iOcupacionId' => 'ocupacion',
            'iGradoInstId' => 'grado',
            'iTipoIeEstId' => 'tipo de i.e.',
        ];
    }
}
