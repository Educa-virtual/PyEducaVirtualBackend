<?php

namespace App\Http\Requests\acad;

use App\Http\Requests\GeneralFormRequest;

class FechasImportantesRequest extends GeneralFormRequest
{
    public function rules(): array
    {
        return [
            //'iFechaImpId'               => 'required|string',
            'iTipoFerId'                => 'required|integer', //
            'iCalAcadId'                => 'required|string', //
            'bFechaImpSeraLaborable'    => 'required|boolean', //
            'cFechaImpNombre'           => 'required|string|max:350', //
            'dtFechaImpFecha'           => 'required|date', //
            'cFechaImpURLDocumento'     => 'nullable|string|max:350', //
            'cFechaImpInfoAdicional'    => 'nullable|string|max:350', //
            'iCredEntPerfId'            => 'required|string',   
            'iDepFechaImpId'            => 'nullable|string',
        ];
    }
    public function messages(): array
    {
        return [
            //'iFechaImpId' => 'Falta complentar el campo iFechaImpId',
            'iTipoFerId.required' => 'Falta complentar el campo Tipo de Feriado',
            'iCalAcadId.required' => 'Falta complentar el campo Calendario Académico',
            //'bFechaImpSeraLaborable' => 'Falta complentar el campo recuperable',
            'cFechaImpNombre.required' => 'Falta complentar el campo nombre',
            'cFechaImpNombre.max' => 'El nombre no debe superar los 350 caracteres',
            'dtFechaImpFecha.required' => 'Falta complentar el campo de Fecha',
            'cFechaImpURLDocumento.required' => 'Falta complentar el campo URL del documento',
            'cFechaImpURLDocumento.max' => 'La URL no debe superar los 350 caracteres',
            'cFechaImpInfoAdicional.max' => 'El campo de infomrmacion adicional no debe superar los 350 caracteres',
            'iCredEntPerfId.required' => 'Falta complentar el campo credencial de entidad',
            //'iDepFechaImpId' => 'Falta complentar el campo iDepFechaImpId',
        ];
    }
}
