<?php

namespace App\Traits;

use Exception;
use Illuminate\Database\QueryException;

trait handleErrorDb
{

    protected function returnError(Exception $e, $defaultMessage = '')
    {
        if ($e instanceof QueryException && isset($e->errorInfo)) {
            $errorInfo = $e->errorInfo;
            $defaultMessage = substr($errorInfo[2], 54);
            return $defaultMessage;
        }
    }
}
