<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AbstractDatabaseOperation;
use Illuminate\Http\Request;

class SelectOperation extends AbstractDatabaseOperation
{
    protected function getProcedureName(): string
    {
        return 'grl.SP_SEL_DesdeTablaOVista';
    }

    protected function getParams(): array
    {
         // Lista de propiedades requeridas
         return ['esquema', 'tabla', 'campos', 'where'];

    }
}
