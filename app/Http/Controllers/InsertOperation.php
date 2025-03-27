<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AbstractDatabaseOperation;

class InsertOperation extends AbstractDatabaseOperation
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function getRequest(): Request
    {
        return $this->request;
    }

    protected function getProcedureName(): string
    {
        return 'grl.SP_INS_EnTablaDesdeJSON';
    }

    protected function getParamsRequest(): array
    {
         // Lista de propiedades requeridas
         return ['esquema', 'tabla', 'campos'];

    }

    protected function getParamsProcedure(): array
    {
        return $this->getParamsRequest();
    }
}

?>