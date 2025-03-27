<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AbstractDatabaseOperation;

class DeleteOperation extends AbstractDatabaseOperation
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
        return 'grl.SP_DEL_RegistroConTransaccion';
    }

    protected function getParamsRequest(): array
    {
         // Lista de propiedades requeridas
         return ['esquema', 'tabla', 'campoId', 'valorId', 'tablaHija'];

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

?>