<?php

namespace App\Http\Docs;

/**
 * @OA\Info(
 *      title="Proyecto Educativo Virtual - API",
 *      version="1.0.0",
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 * @OA\Parameter(
 *         parameter="iCredEntPerfId",
 *         name="iCredEntPerfId",
 *         in="header",
 *         required=true,
 *         description="Id del perfil seleccionado del usuario",
 *         @OA\Schema(type="integer")
 * )
 * @OA\Parameter(
 *     name="Accept",
 *     in="header",
 *     required=true,
 *     description="Tipo de respuesta esperada",
 *     @OA\Schema(type="string", default="application/json")
 * )
 * @OA\Response(
 *     response="204",
 *     description="Registro eliminado",
 *     @OA\JsonContent(
 *         @OA\Property(property="status", type="string", example="Success")
 *     )
 * )
* @OA\Response(
 *     response="400",
 *     description="Error en la solicitud",
 *     @OA\JsonContent(
 *         @OA\Property(property="status", type="string", example="Error"),
 *         @OA\Property(property="message", type="string", example="Mensaje de error de la solicitud")
 *     )
 * )
 * @OA\Response(
 *     response="403",
 *     description="Sin autorización",
 *     @OA\JsonContent(
 *         @OA\Property(property="status", type="string", example="Error"),
 *         @OA\Property(property="message", type="string", example="No tiene permiso para realizar esta acción")
 *     )
 * )
 * @OA\Response(
 *     response="422",
 *     description="Error de validación",
 *      @OA\JsonContent(
 *         @OA\Property(property="status", type="string", example="Error"),
 *         @OA\Property(property="message", type="string", example="Mensaje de error de validación"),
 *     )
 * )
 * @OA\Tag(
 *     name="Buzón de sugerencias",
 *     description="Operaciones relacionadas con el registro de sugerencias por parte de estudiantes"
 * )
 * @OA\Tag(
 *     name="Autenticación",
 *     description="Inicio de sesión de usuarios"
 * )
 * @OA\Tag(
 *     name="Gestión de usuarios",
 *     description="Operaciones relacionadas con la gestión de usuarios del sistema"
 * )
 */
final class BaseDoc {}
