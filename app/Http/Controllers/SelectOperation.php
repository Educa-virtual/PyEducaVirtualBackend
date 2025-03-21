<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AbstractDatabaseOperation;
use Illuminate\Http\Request;

class SelectOperation extends AbstractDatabaseOperation
{
    public const PROCEDURE = 'grl.SP_SEL_DesdeTablaOVista';

    private const STRUCTURE = [
        'esquema' => 'required|string|max:50',
        'tabla' => 'required|string|max:255',
        'campos' => 'nullable',
        'where' => 'nullable',
    ];

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function getRequest(): Request
    {
        return $this->request;
    }

    protected function getValidationRules(): array
    {
        return self::STRUCTURE;
    }

    protected function getProcedureName(): string
    {
        return self::PROCEDURE;
    }

    protected function getParamsRequest(): array
    {
        // Lista de propiedades requeridas
        return array_keys($this->getValidationRules());
    }
    protected function getParamsProcedure(): array
    {
        return array_values(
            [
                'esquema' => $this->request->input('esquema'),
                'tabla' => $this->request->input('tabla'),
                'campos' => $this->request->input('campos'),
                'where' => $this->request->input('where'),
            ]
        );
    }
}
