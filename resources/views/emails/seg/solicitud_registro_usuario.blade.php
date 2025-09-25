@extends('emails.layouts.no-reply')
@section('body')
    <p>Hola <strong>{{ mb_strtoupper($nombre) }}</strong>,
    </p>
    <p>Hemos recibido tu solicitud para la creación de un usuario en el sistema <strong>{{ config('app.name') }}</strong>.
    </p>
    <p>Nuestro equipo revisará la información y, si todo está correcto, recibirás otro correo con las credenciales de
        acceso.</p>
    <p>Atentamente, <br>
        El equipo de Soporte</p>
@endsection
