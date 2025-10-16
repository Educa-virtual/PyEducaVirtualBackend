@extends('emails.layouts.no-reply')

@section('body')
<p>Estimado(a) <strong>{{ $participante->cPersNombre }}</strong>,</p>

@if($estado === 'aprobado')
<p>¡Felicidades! Tu inscripción a la capacitación
  <strong>{{ $capacitacion->cCapacitacionNombre }}</strong> ha sido <strong>aprobada</strong>.
</p>

<p>Pronto recibirás más detalles sobre el inicio del curso.</p>
@else
<p>Tu inscripción a la capacitación
  <strong>{{ $capacitacion->cCapacitacionNombre }}</strong> ha sido <strong>rechazada</strong>.
</p>

<p>Por favor comunícate con soporte si crees que esto fue un error.</p>
@endif

<p>Gracias por tu interés.</p>
@endsection