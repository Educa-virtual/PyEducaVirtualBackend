<?php

namespace App\Http\Controllers;

use App\Contracts\DataReturnStrategy;
use App\Helpers\JsonResponseStrategy;
use App\Traits\ApiResponser;
use App\Traits\handleErrorDb;
use App\Traits\HashidsTrait;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use ApiResponser;
    use handleErrorDb;
    use HashidsTrait;
    use HelperTrait;

    private $strategy;

    public function __construct(DataReturnStrategy $strategy = new JsonResponseStrategy)
    {
        $this->initializeHashids();
        $this->strategy = $strategy;
    }

    public function getData(Request $request)
    {

        return (new SelectOperation($request))->handleRequest($request, $this->strategy);
    }

    public function insertData(Request $request)
    {
        return (new InsertOperation($request))->handleRequest($request, $this->strategy);
    }

    public function updateData(Request $request)
    {
        return (new UpdateOperation($request))->handleRequest($request, $this->strategy);
    }

    public function deleteData(Request $request)
    {
        return (new DeleteOperation($request))->handleRequest($request, $this->strategy);
    }

    public function execProcedure(Request $request, $procedure, $paramsRequest, $paramsProcedure)
    {
        return (new ExecProcedure($procedure, $paramsRequest, $paramsProcedure))->handleRequest($request, $this->strategy);
    }
}
