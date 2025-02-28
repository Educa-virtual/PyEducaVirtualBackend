<?php
namespace App\Helpers;

use App\Contracts\DataReturnStrategy;
use Illuminate\Support\Collection;

class CollectionStrategy implements DataReturnStrategy
{
    public function handle(Collection $data): Collection
    {
        return $data;
    }
}

?>