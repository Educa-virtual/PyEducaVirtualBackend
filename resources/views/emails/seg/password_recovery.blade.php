@extends('emails.layouts.no-reply')
@section('body')
    <p>Hola <strong>{{ $usuario->cPersNombre }}</strong>,
    </p>
    <p>Recibimos una solicitud para recuperar su contraseña.</p>
    <p>Su código de verificación es:</p>

    <h1 style="color:#2d3748; font-size: 32px; letter-spacing: 4px;">
        {{ $token }}
    </h1>

    <p>Este código es válido por 10 minutos.</p>
    <p>Si no hizo esta solicitud, puede ignorar este mensaje.</p>
@endsection
