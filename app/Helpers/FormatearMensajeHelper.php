<?php

namespace App\Helpers;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;

class FormatearMensajeHelper
{
    /**
     * Formatea los mensajes de error de excepciones.
     * @param Exception $exception mensaje de error a formatear.
     * @return \Illuminate\Http\JsonResponse Mensaje de error formateado en JSON.
     */
    public static function error(Exception $exception)
    {
        if ($exception instanceof AuthorizationException) {
            $codigo = Response::HTTP_FORBIDDEN;
            $message = 'No tiene permiso para realizar esta acción';
        } else {
            if (is_string($exception->getMessage())) {
                $mensajeError = $exception;
            } else {
                $mensajeError = json_encode($exception->getMessage());
            }

            if (stripos($mensajeError, 'SQLSTATE') !== false) {
                $codigo = Response::HTTP_BAD_REQUEST;
                $posicionInicio = strripos($mensajeError, 'SQL Server]') + 11;
                $posicionCierre = stripos($mensajeError, '(Connection: sqlsrv');
                $message = substr($mensajeError, $posicionInicio, $posicionCierre - $posicionInicio);
            } else {
                $codigo = $exception->getCode();
                $message = $exception->getMessage();
            }
        }
        return response()->json(['status' => 'Error', 'message' => $message, 'data' => ''], $codigo);
    }

    public static function ok($message, $data = null, $code = Response::HTTP_OK)
    {
        return response()->json(['status' => 'Success', 'message' => $message, 'data' => $data], $code);
    }
}
