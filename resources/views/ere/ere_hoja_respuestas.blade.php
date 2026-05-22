@extends('layouts.pdf')

@section('title', 'HOJA DE RESPUESTAS')

@section('content')

<style>
    @page {
        margin-top: 4cm;
        margin-bottom: 1.5cm;
        margin-left: 1.5cm;
        margin-right: 2cm;
    }
</style>


<main class="container-fluid">

<h1 class="text-center">HOJA DE RESPUESTAS</h1>
<h2 class="text-center">{{ $parametros->cEvaluacionNombre }}</h2>

<br>

<table class="table table-borderless table-condensed table-sm py-4 font-lg">
    <thead>
        <tr>
            <th class="align-middle text-left" width="30%">PRUEBA DE:</th>
            <td class="align-middle text-center border-bottom" colspan="3">{{ $parametros->cCursoNombre ?? '' }}</td>
        </tr>
        <tr>
            <th class="align-middle text-left" width="30%">INSTITUCIÓN EDUCATIVA:</th>
            <td class="align-middle text-center border-bottom" colspan="3">{{ $parametros->cIieeCodigoNombre ?? '' }}</td>
        </tr>
        <tr>
            <th class="align-middle text-left" width="30%">APELLIDOS Y NOMBRES:</th>
            <td class="align-middle text-center border-bottom" colspan="3"></td>
        </tr>
        <tr>
            <th class="align-middle text-left" width="30%">GRADO:</th>
            <td class="align-middle text-center border-bottom" width="25%">{{ $parametros->cGradoNombre ?? '' }}</td>
            <th class="align-middle text-left" width="15%">SECCIÓN:</th>
            <td class="align-middle text-center border-bottom"></td>
        </tr>
    </thead>
</table>

<br>

<h2>INSTRUCCIONES</h3>
<p class="font-lg">Lee muy bien la pregunta del cuadernillo antes de contestar.</p>
<p class="font-lg">Marque con (X) la respuesta correcta.</p>

<br>

@if( $parametros->iExamenCantidadPreguntas > 0 && isset($parametros->alternativas) )

    @php
        $alternativas = json_decode($parametros->alternativas);
        $mitad1 = ceil($parametros->iExamenCantidadPreguntas / 2);
        $mitad2 = floor($parametros->iExamenCantidadPreguntas / 2);
    @endphp

    <table class="table table-borderless table-condensed table-sm py-4 font-lg">
        <tbody>
            @for($i = 1; $i <= $mitad1; $i++)
                <tr height="40">
                    <td class="align-middle text-center border font-weight-bold {{ $i % 2 == 0 ? 'bg-light' : '' }}">{{ $i }}</td>
                    @foreach($alternativas as $alternativa)
                        <td class="align-middle text-center border {{ $i % 2 == 0 ? 'bg-light' : '' }}">{{ $alternativa->cAlternativaLetra ?? '' }}</td>
                    @endforeach
                    <td class="bg-none" width="15%"></td>
                    @if ($i <= $mitad2)
                        <td class="align-middle text-center border font-weight-bold {{ $i % 2 == 0 ? 'bg-light' : '' }}">{{ $mitad1 + $i }}</td>
                        @foreach(json_decode($parametros->alternativas) as $alternativa)
                            <td class="align-middle text-center border {{ $i % 2 == 0 ? 'bg-light' : '' }}">{{ $alternativa->cAlternativaLetra ?? '' }}</td>
                        @endforeach
                    @endif
                </tr>
            @endfor
        </tbody>
    </table>

@else

    <p class="text-center font-lg">Aún no hay preguntas para contestar.</p>

@endif

</main>

@endsection