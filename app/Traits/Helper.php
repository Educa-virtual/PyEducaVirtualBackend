<?php

namespace App\Traits;

use Carbon\Carbon;

trait Helper
{
    protected function getDate() {}

    protected function getDateToDB()
    {
        return Carbon::now()->format('Y-d-m H:i:s');
    }

    protected function decodeId($encodedId)
    {
        $decodedId = $this->hashids->decode($encodedId);
        return count($decodedId) > 0 ? $decodedId[0] : $encodedId;
    }
}
