<?php
namespace App\Enums;

enum Perfil: int
{
    case ADMINISTRADOR = 1;
    case ESPECIALISTA_DREMO = 2;
    case ESPECIALISTA_UGEL = 3;
    case DIRECTOR_IE = 4;
    case SUBDIRECTOR_IE = 5;
    case JEFE_DE_PROGRAMA = 6;
    case DOCENTE = 7;
    case ESTUDIANTE = 80;
    case ADMINISTRADOR_DREMO = 214;
    case APODERADO = 90;
    case ASISTENTE_SOCIAL = 100;
}

