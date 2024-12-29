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

    public function __construct()
    {
        $this->initializeHashids();
    }

    public function getData(Request $request)
    {
        $strategy = new JsonResponseStrategy();

        return (new SelectOperation())->handleRequest($request, $strategy);
    }

    public function insertData(Request $request)
    {
        $strategy = new JsonResponseStrategy();

        return (new InsertOperation())->handleRequest($request, $strategy);
    }

    public function updateData(Request $request)
    {
        $strategy = new JsonResponseStrategy();

        return (new UpdateOperation())->handleRequest($request, $strategy);
    }

    public function deleteData(Request $request)
    {
        $strategy = new JsonResponseStrategy();

        return (new DeleteOperation())->handleRequest($request, $strategy);
    }
}
