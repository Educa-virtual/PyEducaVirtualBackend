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
        return (new SelectOperation())->handleRequest($request, $this->strategy);
    }

    public function insertData(Request $request)
    {
        return (new InsertOperation())->handleRequest($request, $this->strategy);
    }

    public function updateData(Request $request)
    {
        return (new UpdateOperation())->handleRequest($request, $this->strategy);
    }

    public function deleteData(Request $request)
    {
        return (new DeleteOperation())->handleRequest($request, $this->strategy);
    }
}
