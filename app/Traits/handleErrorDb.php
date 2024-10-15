<?php

namespace App\Traits;

use Exception;
use Throwable;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Log;

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

    protected function handleAndLogError(Throwable $e, $defaultMessage = 'Ha ocurrido un error')
    {
        $logMessage = '';
        $returnMessage = '';

        $context = [
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => array_slice($e->getTrace(), 0, 5)
        ];


        if ($e instanceof QueryException) {
            // Errores de base de datos
            $errorInfo = $e->errorInfo;
            $errorCode = $e->getCode();
            if ($errorCode >= 50000) {
                $returnMessage = substr($errorInfo[2] ?? $defaultMessage, 0, 200);
            } else {
                $returnMessage = $defaultMessage;
            }
            $context['sql'] = $e->getSql();
            $context['bindings'] = $e->getBindings();
            $logMessage = "Error de base de datos: " . $e->getMessage();
        } elseif ($e instanceof ValidationException) {
            // Errores de validación
            $returnMessage = $e->errors();
            $logMessage = "Error de validación: " . json_encode($e->errors());
        } elseif ($e instanceof HttpException) {
            // Errores HTTP
            $returnMessage = $e->getMessage() ?: $defaultMessage;
            $logMessage = "Error HTTP " . $e->getStatusCode() . ": " . $e->getMessage();
            $context['status_code'] = $e->getStatusCode();
        } elseif (method_exists($e, 'getMessage')) {
            // Otros tipos de excepciones
            $returnMessage = $e->getMessage() ?: $defaultMessage;
            $logMessage = "Excepción: " . get_class($e) . " - " . $e->getMessage();
        } else {
            // Si no se pudo determinar el error específico
            $returnMessage = $defaultMessage;
            $logMessage = "Error desconocido: " . $defaultMessage;
        }

        // Registrar el error en el log
        Log::error($logMessage, $context);


        return $returnMessage;
    }
}
