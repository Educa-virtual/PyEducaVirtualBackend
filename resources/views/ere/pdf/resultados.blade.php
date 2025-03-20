@extends('layouts.pdf')

@section('title', 'RESULTADOS ERE')

@section('content')

<style>
    @page {
        margin-top: 4cm;
        margin-bottom: 1.5cm;
        margin-left: 1.5cm;
        margin-right: 2cm;
    }

    header {
        position: fixed;
        top: -2.5cm;
        left: 0cm;
        right: 0cm;
        height: 2cm;
    }
</style>

<header>
    <main class="container-fluid">
        <table class="table table-bordered table-condensed text-center table-sm py-2">
            <tr>
                <td width="15%" class="text-left align-middle"><img src="{{ public_path('images/logo-dremo.png') }}" width="100%"></td>
                <td width="80%" class="text-center align-middle font-xl">RESULTADOS DE EVALUACIÓN ERE</td>
                <td width="5%" class="text-right align-middle"><img src="{{ public_path('images/logo-plataforma-virtual.png') }}" width="100%"></td>
            </tr>
        </table>
    </main>
</header>

<footer class="container-fluid">
    <table class="table table-borderless table-condensed table-sm py-2">
        <tr>
            <td width="80%" class="text-left font-weight-bold">IMPRESO EL {{ date('d/m/Y') }} A LAS {{ date('h:i') }}</td>
            <td width="20%" class="text-right font-weight-bold">PÁGINA <span class="paginacion"></span></td>
        </tr>
    </table>
</footer>

<main class="container-fluid">

<table class="table table-borderless table-condensed table-sm py-4">
    <thead>
        <tr>
            <th class="align-middle bg-light text-left" width="5%">EVALUACIÓN:</th>
            <td class="align-middle text-left">{{ $filtros->evaluacion }}</td>
            @isset( $filtros->cod_ie )
                <th class="align-middle bg-light text-left" width="5%">I.E.:</th>
                <td class="align-middle text-left">{{ $filtros->cod_ie }}</td>
            @endisset
            @isset( $filtros->sector )
                <th class="align-middle bg-light text-left" width="5%">Sector:</th>
                <td class="align-middle text-left">{{ $filtros->sector }}</td>
            @endisset
        </tr>
        <tr>
            <th class="align-middle bg-light text-left" width="5%">CURSO:</th>
            <td class="align-middle text-left">{{ $filtros->curso }}</td>
            @isset( $filtros->distrito )
                <th class="align-middle bg-light text-left" width="5%">DISTRITO:</th>
                <td class="align-middle text-left">{{ $filtros->distrito }}</td>
            @endisset
            @isset( $filtros->zona )
                <th class="align-middle bg-light text-left" width="5%">ZONA:</th>
                <td class="align-middle text-left">{{ $filtros->zona }}</td>
            @endisset
        </tr>
        <tr>
            <th class="align-middle bg-light text-left" width="5%">GRADO:</th>
            <td class="align-middle text-left">{{ $filtros->grado }}</td>
            @isset( $filtros->seccion )
                <th class="align-middle bg-light text-left" width="5%">SECCION:</th>
                <td class="align-middle text-left">{{ $filtros->seccion }}</td>
            @endisset
            @isset( $filtros->sexo )
                <th class="align-middle bg-light text-left" width="5%">SEXO:</th>
                <td class="align-middle text-left">{{ $filtros->sexo }}</td>
            @endisset
        </tr>
    </thead>
</table>

<br>

<table class="table table-bordered table-condensed table-sm py-4">
    <thead>
        <tr>
            <th class="font-lg bg-light text-center" colspan="{{ $nro_preguntas + 9 }}">RESULTADOS DE ESTUDIANTES</th>
        </tr>
        <tr>
            <th class="align-middle bg-light text-center" width="2%">#</th>
            <th class="align-middle bg-light text-center" width="10%">I.E.</th>
            <th class="align-middle bg-light text-center" width="5%">DISTRITO</th>
            <th class="align-middle bg-light text-center" width="2%">SECCION</th>
            <th class="align-middle bg-light text-center" width="25%">ESTUDIANTE</th>
            @for ($pregunta = 1; $pregunta <= $nro_preguntas; $pregunta++ )
                <th class="align-middle bg-light text-center">{{ $pregunta }}</th>
            @endfor
            <th class="align-middle bg-light text-center" width="5%">ACIERTOS</th>
            <th class="align-middle bg-light text-center" width="5%">DESACIERTOS</th>
            <th class="align-middle bg-light text-center" width="5%">BLANCOS</th>
            <th class="align-middle bg-light text-center" width="5%">NIVEL</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $resultados as $resultado )
            <tr>
                <td class="align-middle text-center">{{ $loop->iteration }}</td>
                <td class="align-middle text-left">{{ $resultado->cod_ie }}</td>
                <td class="align-middle text-left">{{ $resultado->distrito }}</td>
                <td class="align-middle text-center">{{ $resultado->seccion }}</td>
                <td class="align-middle text-left">{{ $resultado->estudiante }}</td>
                @foreach (json_decode($resultado->respuestas) as $value)
                    <td class="align-middle text-center {{ $value->correcta == true ? 'text-success bg-light' : 'text-danger' }}">
                        {{ $value->respuesta }}
                    </td>
                @endforeach
                <td class="align-middle text-center">{{ (int) $resultado->aciertos }}</td>
                <td class="align-middle text-center">{{ (int) $resultado->desaciertos }}</td>
                <td class="align-middle text-center">{{ (int) $resultado->blancos }}</td>
                <td class="align-middle text-center">{{ $resultado->nivel_logro }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>

<table class="table-aside table-bordered table-condensed table-sm py-4 text-right">
    <thead>
        <tr>
            <th class="font-lg bg-light text-center" colspan="2">RESUMEN DE NIVELES DE LOGRO</th>
        </tr>
        <tr>
            <th class="align-middle bg-light text-center" width="60%">NIVEL</th>
            <th class="align-middle bg-light text-center" width="40%">CANTIDAD</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $niveles as $nivel => $cantidad )
            <tr>
                <td class="align-middle text-left">{{ $nivel }}</td>
                <td class="align-middle text-center">{{ $cantidad }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <th class="align-middle bg-light text-center" width="60%">TOTAL</th>
        <th class="align-middle bg-light text-center" width="60%">{{ count($resultados) }}</th>
    </tfoot>
</table>

<div class="page-break"></div>

<table class="table table-bordered table-condensed table-sm py-4">
    <thead>
        <tr>
            <th class="font-lg bg-light text-center" colspan="{{ $nro_preguntas + 1 }}">RESULTADOS RESUMIDOS POR PREGUNTA</th>
        </tr>
        <tr>
            <th class="align-middle bg-light text-center" width="10%">MÉTRICA</th>
            @for ($pregunta = 1; $pregunta <= $nro_preguntas; $pregunta++ )
                <th class="align-middle bg-light text-center">{{ $pregunta }}</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach ( $resumen as $item )
            <tr>
                <td class="align-middle text-left">{{ $item->metrica }}</td>
                @foreach ($item as $key => $columna)
                    @if( $key != 'metrica' )
                        @if( stripos($item->metrica, '%') === false )
                            <td class="align-middle text-center">{{ (int) $columna }}</td>
                        @else
                            <td class="align-middle text-center">{{ $columna }}</td>
                        @endif
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

<div class="page-break"></div>

<table class="table table-bordered table-condensed table-sm py-4">
    <thead>
        <tr>
            <th class="font-lg bg-light text-center" colspan="7">RESULTADOS SEGÚN COMPETENCIAS</th>
        </tr>
        <tr>
            <th class="align-middle bg-light text-center" width="5%">PREGUNTA</th>
            <th class="align-middle bg-light text-center" width="25%">COMPETENCIA</th>
            <th class="align-middle bg-light text-center" width="50%">DESEMPEÑO</th>
            <th class="align-middle bg-light text-center" width="5%">ACIERTOS</th>
            <th class="align-middle bg-light text-center" width="5%">DESACIERTOS</th>
            <th class="align-middle bg-light text-center" width="5%">% DE ACIERTOS</th>
            <th class="align-middle bg-light text-center" width="5%">% DE DESACIERTOS</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $matriz as $item )
            <tr>
                <td class="align-middle text-center">{{ $item->pregunta_nro }}</td>
                <td class="align-middle text-left">{{ $item->competencia }}</td>
                <td class="align-middle text-left">{{ $item->desempeno }}</td>
                <td class="align-middle text-center">{{ $item->aciertos }}</td>
                <td class="align-middle text-center">{{ $item->desaciertos }}</td>
                <td class="align-middle text-center">{{ $item->porcentaje_aciertos }}</td>
                <td class="align-middle text-center">{{ $item->porcentaje_desaciertos }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</main>

@endsection