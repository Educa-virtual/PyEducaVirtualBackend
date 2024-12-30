<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AbstractDatabaseOperation;

class InsertOperation extends AbstractDatabaseOperation
{
    protected function getProcedureName(): string
    {
        return 'grl.SP_INS_EnTablaDesdeJSON';
    }

    protected function getParams(): array
    {
         // Lista de propiedades requeridas
         return ['esquema', 'tabla', 'campos'];

    }
}

?>