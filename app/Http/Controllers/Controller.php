<?php

namespace App\Http\Controllers;

use App\Contracts\DataReturnStrategy;
use App\Helpers\JsonResponseStrategy;
use Exception;
use App\Helpers\ResponseHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;

abstract class Controller extends BaseController
{
}
