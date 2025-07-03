<?php

namespace App\Http\Controllers;

use App\Traits\HelperTrait;
use App\Traits\ApiResponser;
use App\Traits\HashidsTrait;
use Illuminate\Http\Request;
use App\Traits\handleErrorDb;
use App\Contracts\DataReturnStrategy;
use App\Helpers\JsonResponseStrategy;
use App\Http\Controllers\DeleteOperation;
use App\Http\Controllers\InsertOperation;
use App\Http\Controllers\SelectOperation;
use App\Http\Controllers\UpdateOperation;
use Symfony\Component\Console\Output\ConsoleOutput;

class ApiController extends Controller
{
    use ApiResponser;
    use handleErrorDb;
    use HelperTrait;
    use HashidsTrait;

    private $strategy;

    public function __construct(DataReturnStrategy $strategy = new JsonResponseStrategy())
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
    public function execProcedure(Request $request, $procedure, $paramsRequest, $paramsProcedure){
        return (new ExecProcedure($procedure, $paramsRequest, $paramsProcedure))->handleRequest($request, $this->strategy);
    }
}
