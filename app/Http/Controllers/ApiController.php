<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use App\Traits\handleErrorDb;
use App\Traits\Helper as TraitsHelper;

class ApiController extends Controller
{
    use ApiResponser;
    use handleErrorDb;
    use TraitsHelper;
}
