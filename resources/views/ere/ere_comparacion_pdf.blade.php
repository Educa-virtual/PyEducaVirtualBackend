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
</style>

<main class="container-fluid">

<table class="table table-borderless table-condensed table-sm py-4">
    <thead>
        <tr>
            <th class="align-middle bg-light text-left" width="9%">EVALUACIÓN 1:</th>
            <td class="align-middle text-left">{{ $filtros->evaluacion ?? '' }}</td>
            <th class="align-middle bg-light text-left" width="9%">EVALUACIÓN 2:</th>
            <td class="align-middle text-left">{{ $filtros->evaluacion2 ?? '' }}</td>
        </tr>
        <tr>
            @isset( $filtros->cod_ie )
                <th class="align-middle bg-light text-left" width="9%">I.E.:</th>
                <td class="align-middle text-left">{{ $filtros->cod_ie }}</td>
            @endisset
            @isset( $filtros->sector )
                <th class="align-middle bg-light text-left" width="9%">GESTIÓN:</th>
                <td class="align-middle text-left">{{ $filtros->sector }}</td>
            @endisset
            @isset( $filtros->zona )
                <th class="align-middle bg-light text-left" width="9%">ZONA:</th>
                <td class="align-middle text-left">{{ $filtros->zona }}</td>
            @endisset
        </tr>
        <tr>
            <th class="align-middle bg-light text-left" width="9%">ÁREA:</th>
            <td class="align-middle text-left">{{ $filtros->curso ?? '' }}</td>
            @if( isset($filtros->ugel) || isset($filtros->distrito) )
                <th class="align-middle bg-light text-left" width="9%">UGEL/DISTRITO:</th>
                <td class="align-middle text-left">{{ $filtros->ugel ?? '' }} - {{ $filtros->distrito ?? '' }}</td>
            @endif
        </tr>
        <tr>
            <th class="align-middle bg-light text-left" width="9%">NIVEL/GRADO:</th>
            <td class="align-middle text-left">{{ $filtros->nivel ?? '' }} - {{ $filtros->grado ?? '' }}</td>
            @isset( $filtros->seccion )
                <th class="align-middle bg-light text-left" width="9%">SECCION:</th>
                <td class="align-middle text-left">{{ $filtros->seccion }}</td>
            @endisset
            @isset( $filtros->sexo )
                <th class="align-middle bg-light text-left" width="9%">SEXO:</th>
                <td class="align-middle text-left">{{ $filtros->sexo }}</td>
            @endisset
        </tr>
    </thead>
</table>

<br>

<table class="table table-bordered table-condensed table-sm py-4 font-lg text-right">
    <thead>
        <tr>
            <th class="bg-light text-center" colspan="5">RESUMEN DE NIVELES DE LOGRO</th>
        </tr>
        <tr>
            <th class="align-middle bg-light text-center" width="20%" rowspan="2">NIVEL</th>
            <th class="align-middle bg-light text-center" width="40%" colspan="2">{{ $filtros->evaluacion }}</th>
            <th class="align-middle bg-light text-center" width="40%" colspan="2">{{ $filtros->evaluacion2 }}</th>
        </tr>
        <tr>
            <th class="align-middle bg-light text-center" width="20%">CANTIDAD</th>
            <th class="align-middle bg-light text-center" width="20%">PORCENTAJE</th>
            <th class="align-middle bg-light text-center" width="20%">CANTIDAD</th>
            <th class="align-middle bg-light text-center" width="20%">PORCENTAJE</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $niveles as $item )
            <tr>
                <td class="align-middle text-left">{{ $item['nivel'] }}</td>
                <td class="align-middle text-center">{{ $item['cantidad1'] }}</td>
                <td class="align-middle text-center">{{ $item['porcentaje1'] }}</td>
                <td class="align-middle text-center">{{ $item['cantidad2'] }}</td>
                <td class="align-middle text-center">{{ $item['porcentaje2'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <th class="align-middle bg-light text-center">TOTAL ESTUDIANTES</th>
        <th class="align-middle bg-light text-center">{{ $total1 }}</th>
        <th class="align-middle bg-light text-center"></th>
        <th class="align-middle bg-light text-center">{{ $total2 }}</th>
        <th class="align-middle bg-light text-center"></th>
    </tfoot>
</table>

<div class="page-break"></div>

@php
    $otras_columnas = 9;
    if( !isset($filtros->cod_ie) && !isset($filtros->distrito) ) {
        $otras_columnas = 9;
    } elseif( isset($filtros->distrito) && !isset($filtros->cod_ie) ) {
        $otras_columnas = 8;
    } else {
        $otras_columnas = 7;
    }
@endphp

<table class="table table-bordered table-condensed table-sm py-4">
    <thead>
        <tr>
            <th class="font-lg bg-light text-center" colspan="{{ 20 + $otras_columnas }}">RESULTADOS DE ESTUDIANTES - {{ $filtros->evaluacion }}</th>
        </tr>
        <tr>
            <th class="align-middle bg-light text-center" width="3%">#</th>
            @if( !isset($filtros->cod_ie) && !isset($filtros->distrito) )
                <th class="align-middle bg-light text-center" width="8%">I.E.</th>
                <th class="align-middle bg-light text-center" width="8%">DISTRITO</th>
            @elseif( isset($filtros->distrito) && !isset($filtros->cod_ie) )
                <th class="align-middle bg-light text-center" width="8%">DISTRITO</th>
            @endif
            <th class="align-middle bg-light text-center font-xs" width="4%">SECCIÓN</th>
            @if( isset($filtros->cod_ie) && isset($filtros->distrito) )
                <th class="align-middle bg-light text-center" width="26%">ESTUDIANTE</th>
            @elseif( isset($filtros->cod_ie) || isset($filtros->distrito) )
                <th class="align-middle bg-light text-center" width="18%">ESTUDIANTE</th>
            @else
                <th class="align-middle bg-light text-center" width="10%">ESTUDIANTE</th>
            @endif
            @for ($pregunta = 1; $pregunta <= 20; $pregunta++ )
                <th class="align-middle bg-light text-center">{{ $pregunta }}</th>
            @endfor
            <th class="align-middle bg-light text-center font-xs" width="4%">ACIERTOS</th>
            <th class="align-middle bg-light text-center font-xs" width="4%">DESACIERTOS</th>
            <th class="align-middle bg-light text-center font-xs" width="4%">BLANCOS</th>
            <th class="align-middle bg-light text-center" width="8%">NIVEL</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $resultados1 as $resultado )
            <tr>
                <td class="align-middle text-center">{{ $loop->iteration }}</td>
                @if( !isset($filtros->cod_ie) && !isset($filtros->distrito) )
                    <td class="align-middle text-left">{{ $resultado->cod_ie }}</td>
                    <td class="align-middle text-left">{{ $resultado->distrito }}</td>
                @elseif( isset($filtros->distrito) && !isset($filtros->cod_ie) )
                    <td class="align-middle text-left">{{ $resultado->distrito }}</td>
                @endif
                <td class="align-middle text-center">{{ $resultado->seccion }}</td>
                <td class="align-middle text-left">{{ $resultado->estudiante }}</td>
                @foreach ($resultado->respuestas as $value)
                    <td class="align-middle text-center {{ $value->c == 1 ? 'text-success bg-light' : 'text-danger' }}">{{ $value->r }}</td>
                @endforeach
                <td class="align-middle text-center">{{ (int) $resultado->aciertos }}</td>
                <td class="align-middle text-center">{{ (int) $resultado->desaciertos }}</td>
                <td class="align-middle text-center">{{ (int) $resultado->blancos }}</td>
                <td class="align-middle text-center">{{ $resultado->nivel_logro }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="page-break"></div>

<table class="table table-bordered table-condensed table-sm py-4">
    <thead>
        <tr>
            <th class="font-lg bg-light text-center" colspan="{{ 20 + $otras_columnas }}">RESULTADOS DE ESTUDIANTES - {{ $filtros->evaluacion2 }}</th>
        </tr>
        <tr>
            <th class="align-middle bg-light text-center" width="3%">#</th>
            @if( !isset($filtros->cod_ie) && !isset($filtros->distrito) )
                <th class="align-middle bg-light text-center" width="8%">I.E.</th>
                <th class="align-middle bg-light text-center" width="8%">DISTRITO</th>
            @elseif( isset($filtros->distrito) && !isset($filtros->cod_ie) )
                <th class="align-middle bg-light text-center" width="8%">DISTRITO</th>
            @endif
            <th class="align-middle bg-light text-center font-xs" width="4%">SECCIÓN</th>
            @if( isset($filtros->cod_ie) && isset($filtros->distrito) )
                <th class="align-middle bg-light text-center" width="26%">ESTUDIANTE</th>
            @elseif( isset($filtros->cod_ie) || isset($filtros->distrito) )
                <th class="align-middle bg-light text-center" width="18%">ESTUDIANTE</th>
            @else
                <th class="align-middle bg-light text-center" width="10%">ESTUDIANTE</th>
            @endif
            @for ($pregunta = 1; $pregunta <= 20; $pregunta++ )
                <th class="align-middle bg-light text-center">{{ $pregunta }}</th>
            @endfor
            <th class="align-middle bg-light text-center font-xs" width="4%">ACIERTOS</th>
            <th class="align-middle bg-light text-center font-xs" width="4%">DESACIERTOS</th>
            <th class="align-middle bg-light text-center font-xs" width="4%">BLANCOS</th>
            <th class="align-middle bg-light text-center" width="8%">NIVEL</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $resultados2 as $resultado )
            <tr>
                <td class="align-middle text-center">{{ $loop->iteration }}</td>
                @if( !isset($filtros->cod_ie) && !isset($filtros->distrito) )
                    <td class="align-middle text-left">{{ $resultado->cod_ie }}</td>
                    <td class="align-middle text-left">{{ $resultado->distrito }}</td>
                @elseif( isset($filtros->distrito) && !isset($filtros->cod_ie) )
                    <td class="align-middle text-left">{{ $resultado->distrito }}</td>
                @endif
                <td class="align-middle text-center">{{ $resultado->seccion }}</td>
                <td class="align-middle text-left">{{ $resultado->estudiante }}</td>
                @foreach ($resultado->respuestas as $value)
                    <td class="align-middle text-center {{ $value->c == 1 ? 'text-success bg-light' : 'text-danger' }}">{{ $value->r }}</td>
                @endforeach
                <td class="align-middle text-center">{{ (int) $resultado->aciertos }}</td>
                <td class="align-middle text-center">{{ (int) $resultado->desaciertos }}</td>
                <td class="align-middle text-center">{{ (int) $resultado->blancos }}</td>
                <td class="align-middle text-center">{{ $resultado->nivel_logro }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</main>

@endsection