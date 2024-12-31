<?php
namespace App\Helpers;

use App\Contracts\DataReturnStrategy;
use App\Helpers\ResponseHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class JsonResponseStrategy implements DataReturnStrategy
{
    public function handle(Collection $data): JsonResponse
    {
        return ResponseHandler::success($data);
    }
}


?>