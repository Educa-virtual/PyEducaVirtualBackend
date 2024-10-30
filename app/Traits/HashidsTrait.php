<?php

namespace App\Traits;

use Hashids\Hashids;

trait HashidsTrait
{
    protected $hashids;

    public function initializeHashids()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    protected function decodeId($encodedId)
    {
        $decodedId = $this->hashids->decode($encodedId);
        return count($decodedId) > 0 ? $decodedId[0] : $encodedId;
    }
}
