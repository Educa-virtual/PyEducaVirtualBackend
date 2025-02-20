<?php 
namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHandler
{
    /**
     * Formatea una respuesta exitosa.
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function success($data = [], string $message = 'Operación exitosa', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'validated' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Formatea una respuesta de error.
     *
     * @param string $message
     * @param int $statusCode
     * @param mixed $errors
     * @return JsonResponse
     */
    public static function error(string $message = 'Ha ocurrido un error', int $statusCode = 500, $errors = null): JsonResponse
    {
        return response()->json([
            'validated' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}

?>