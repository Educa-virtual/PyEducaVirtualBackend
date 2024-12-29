<?php

namespace App\Contracts;


use Illuminate\Support\Collection;

interface DataReturnStrategy
{
    public function handle(Collection $data): mixed;
}


?>