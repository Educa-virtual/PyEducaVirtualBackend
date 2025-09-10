@extends('emails.layouts.no-reply')
@section('body')
    <p>Hola <strong>{{ $usuario->cPersNombre }}</strong>,
    </p>
    <p>Te informamos que la <strong>contraseña de tu cuenta</strong> ha sido actualizada correctamente.</p>
    <p>Si tú realizaste esta acción, no es necesario hacer nada más.</p>
    <p>Si <strong>no fuiste tú</strong>, por favor <a style="color:#0a66c2; text-decoration:none" href="{{ url('/') }}">restablece tu contraseña</a>
        inmediatamente y contacta a nuestro equipo de soporte enviando un correo a <a style="color:#0a66c2; text-decoration:none"
            href="mailto:soporte@educavirtual.edu.pe">soporte&#64;educavirtual.edu.pe</a>.</p>
@endsection
