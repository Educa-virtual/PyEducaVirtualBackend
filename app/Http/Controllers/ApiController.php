<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use App\Traits\handleErrorDb;
use App\Traits\HashidsTrait;
use App\Traits\HelperTrait;

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
}
