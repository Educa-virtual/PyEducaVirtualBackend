@extends('emails.layouts.no-reply')
@section('body')
    <p>Estimado(a) <strong>{{ $data->cPersNombreApo }} {{$data->cPersPaternoApo}} {{$data->cPersMaternoApo}}</strong></p>
    <p>Le informamos que estudiante <strong>{{ $data->cPersNombreEst }} {{$data->cPersPaternoEst}} {{$data->cPersMaternoEst}}</strong> no asistió a la institución educativa <strong>{{$data->cIieeNombre}}</strong> el día <strong>{{ $fecha }}</strong>.</p>

    <p>Le recordamos la importancia de mantener una asistencia regular, ya que las ausencias reiteradas pueden afectar el desempeño académico del estudiante.
    Si la inasistencia se debió a un motivo justificado, le agradeceremos que lo comunique a la institución a la brevedad posible.</p>

@endsection
