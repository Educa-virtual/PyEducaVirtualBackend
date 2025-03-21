<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AbstractDatabaseOperation;

class UpdateOperation extends AbstractDatabaseOperation
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
        return 'grl.SP_UPD_EnTablaConJSON';
    }

    protected function getParamsRequest(): array
    {
         // Lista de propiedades requeridas
         return ['esquema', 'tabla', 'campos', 'where'];

    }
    protected function getParamsProcedure(): array
    {
        return $this->getParamsRequest();
    }
}

?>