<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\AbstractDatabaseOperation;

class UpdateOperation extends AbstractDatabaseOperation
{
    protected function getProcedureName(): string
    {
        return 'grl.SP_UPD_EnTablaConJSON';
    }
}

?>