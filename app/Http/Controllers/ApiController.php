<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use App\Traits\handleErrorDb;

class ApiController extends Controller
{
    use ApiResponser;
    use handleErrorDb;
}
