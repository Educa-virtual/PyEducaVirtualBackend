<?php

namespace App\Services\acad;

use App\Enums\Perfil;
use App\Helpers\VerifyHash;
use App\Models\acad\Estudiante;
use App\Models\acad\Matricula;
use App\Models\apo\Apoderado;
use App\Models\grl\Persona;
use App\Services\seg\UsuariosService;
use Exception;

class MatriculasService
{
    public static function obtenerDetalleMatriculaEstudiante($params)
    {
        $data = Matricula::selDetalleMatriculaEstudiante($params);
        if (!$data) {
            throw new Exception("No existe una matrícula para el año seleccionado o los parámetros enviados");
        }
        return $data;
    }

    public static function obtenerMatriculaPorId($request)
    {
        return Matricula::selMatriculaPorId($request);
    }

    public static function obtenerCursosMatricula($iMatrId)
    {
        return Matricula::selCursosMatricula($iMatrId);
    }

    public static function obtenerMatriculasEstudiante($request, $hashIds = true)
    {
        $data = Matricula::selMatriculas($request);
        if ($hashIds) {
            foreach ($data as $fila) {
                $fila->iEstudianteId = VerifyHash::encodexId($fila->iEstudianteId);
                $fila->iMatrId = VerifyHash::encodexId($fila->iMatrId);
            }
        }
        return $data;
    }

    public static function registrarApoderado($request)
    {
        if ($request->iPersId == null || $request->iPersId == 0) {
            $persona = Persona::insPersonas($request);
            $request->merge([
                'iPersId' => $persona['iPersId'],
            ]);
        } else {
            Persona::updPersonas($request);
        }
        // Crear credencial de apoderado
        $credencial = UsuariosService::registrarUsuario($request);
        $request->merge([
            'iCredId' => $credencial->iCredId,
            'iPerfilId' => Perfil::APODERADO->value,
        ]);
        UsuariosService::insPerfil($request);

        return Apoderado::insApoderado($request);
    }

    public static function actualizarApoderado($request)
    {
        if ($request->iPersId == null || $request->iPersId == 0) {
            $persona = Persona::insPersonas($request);
            $request->merge([
                'iPersId' => $persona['iPersId'],
            ]);
        } else {
            Persona::updPersonas($request);
        }
        // Crear credencial de apoderado
        $credencial = UsuariosService::registrarUsuario($request);
        $request->merge([
            'iCredId' => $credencial->iCredId,
            'iPerfilId' => Perfil::APODERADO->value,
        ]);
        UsuariosService::insPerfil($request);

        return Apoderado::updApoderado($request);
    }

    public static function registrarMatricula($request)
    {
        if ($request->iPersId == null || $request->iPersId == 0) {
            $persona = Persona::insPersonas($request);
            $request->merge([
                'iPersId' => $persona['iPersId'],
            ]);
        } else {
            Persona::updPersonas($request);
        }

        if($request->iEstudianteId == null || $request->iEstudianteId == 0) {
            $estudiante = Estudiante::insEstudiante($request);
            $request->merge([
                'iEstudianteId' => $estudiante['iEstudianteId'],
            ]);
        } else {
            Estudiante::updEstudiante($request);
        }

        // Crear credencial de estudiante
        $credencial = UsuariosService::registrarUsuario($request);
        $request->merge([
            'iCredId' => $credencial->iCredId,
            'iPerfilId' => Perfil::ESTUDIANTE->value,
        ]);
        UsuariosService::insPerfil($request);

        if($request->iApoderadoId != null || $request->iApoderadoId > 0) {
            // Crear credencial de apoderado
            $request->merge([
                'iPersId' => $request->iPersIdApoderado,
            ]);
            $credencial = UsuariosService::registrarUsuario($request);
            $request->merge([
                'iCredId' => $credencial->iCredId,
                'iPerfilId' => Perfil::APODERADO->value,
            ]);
            UsuariosService::insPerfil($request);

            Apoderado::insApoderado($request);
        }

        return Matricula::insMatricula($request);
    }

    public static function actualizarMatricula($request)
    {
        if ($request->iPersId == null || $request->iPersId == 0) {
            $request->merge([
                'iPersId' => Persona::insPersonas($request),
            ]);
        } else {
            Persona::updPersonas($request);
        }

        if($request->iEstudianteId == null || $request->iEstudianteId == 0) {
            $request->merge([
                'iEstudianteId' => Estudiante::insEstudiante($request),
            ]);
        } else {
            Estudiante::updEstudiante($request);
        }

        // Crear credencial de estudiante
        $credencial = UsuariosService::registrarUsuario($request);
        $request->merge([
            'iCredId' => $credencial->iCredId,
            'iPerfilId' => Perfil::ESTUDIANTE->value,
        ]);
        UsuariosService::insPerfil($request);

        return Matricula::updMatricula($request);
    }
}
