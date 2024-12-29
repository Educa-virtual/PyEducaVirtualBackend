<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\AbstractDatabaseOperation;

class DeleteOperation extends AbstractDatabaseOperation
{
    protected function getProcedureName(): string
    {
        return 'grl.SP_DEL_RegistroConTransaccion';
    }
}

?>