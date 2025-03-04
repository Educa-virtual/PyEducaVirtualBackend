<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AbstractDatabaseOperation;

class SelectOperation extends AbstractDatabaseOperation
{
    public const PROCEDURE = 'grl.SP_SEL_DesdeTablaOVista';

    private const STRUCTURE = [
        'esquema' => 'required|string|max:50',
        'tabla' => 'required|string|max:255',
        'campos' => 'nullable',
        'where' => 'nullable',
    ];
    
    protected function getValidationRules(): array
    {
        return self::STRUCTURE;
    }

    protected function getProcedureName(): string
    {
        return self::PROCEDURE;
    }

    protected function getParams(): array
    {
        // Lista de propiedades requeridas
        return array_keys($this->getValidationRules());
    }
}
