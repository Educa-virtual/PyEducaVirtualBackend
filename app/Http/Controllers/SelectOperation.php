<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\AbstractDatabaseOperation;

class SelectOperation extends AbstractDatabaseOperation
{
    protected function getProcedureName(): string
    {
        return 'grl.SP_SEL_DesdeTablaOVista';
    }
}

?>