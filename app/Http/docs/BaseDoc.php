<?php

namespace App\Http\Docs;

/**
 * @OA\Info(
 *      title="Proyecto Educativo Virtual - API",
 *      version="1.0.0",
 * ),
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * ),
 * @OA\Parameter(
 *         parameter="iCredEntPerfId",
 *         name="iCredEntPerfId",
 *         in="header",
 *         required=true,
 *         description="Id de la credencial a nivel entidad y perfil",
 *         @OA\Schema(type="integer")
 * ),
 * @OA\Parameter(
 *     name="Accept",
 *     in="header",
 *     required=true,
 *     description="Tipo de respuesta esperada",
 *     @OA\Schema(type="string", default="application/json")
 * )
 * @OA\Tag(
 *     name="Buzón de sugerencias",
 *     description="Operaciones relacionadas con el registro de sugerencias por parte de estudiantes"
 * )
 * @OA\Tag(
 *     name="Autenticación",
 *     description="Inicio de sesión de usuarios"
 * )
 */
final class BaseDoc {}
