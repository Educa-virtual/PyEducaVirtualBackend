<?php

namespace App\Traits;

use Carbon\Carbon;

trait HelperTrait
{
    protected function getDate() {}

    protected function getDateToDB()
    {
        return Carbon::now()->format('Y-d-m H:i:s');
    }
}
