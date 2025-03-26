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
        <table class="table table-condensed text-center table-sm py-2 border-bottom">
            <tr>
                <td width="15%" class="text-left align-middle" rowspan="2"><img src="{{ public_path('images/logo-dremo.png') }}" width="100%"></td>
                <td width="70%" class="text-center align-middle">{{ $filtros->year_oficial }}</td>
                <td width="10%" rowspan="2"></td>
                <td width="5%" class="text-right align-middle" rowspan="2"><img src="{{ public_path('images/logo-plataforma-virtual.png') }}" width="100%"></td>
            </tr>
            <tr>
                <td width="80%" class="text-center align-middle font-xl">RESULTADOS DE EVALUACIÓN ERE</td>
            </tr>
        </table>
    </main>
</header>

<footer class="container-fluid">
    <table class="table table-borderless table-condensed table-sm py-2">
        <tr>
            <td width="20%" class="text-right font-weight-bold">PÁGINA <span class="paginacion"></span></td>
            <td width="60%" class="text-center font-weight-bold">AUTOR</td>
            <td width="20%" class="text-left font-weight-bold">{{ date('d/m/Y') }} A LAS {{ date('h:i') }}</td>
        </tr>
    </table>
</footer>

<main class="container-fluid">

<table class="table table-borderless table-condensed table-sm py-4">
    <thead>
        <tr>
            <th class="align-middle bg-light text-left" width="8%">EVALUACIÓN:</th>
            <td class="align-middle text-left">{{ $filtros->evaluacion }}</td>
            @isset( $filtros->cod_ie )
                <th class="align-middle bg-light text-left" width="8%">I.E.:</th>
                <td class="align-middle text-left">{{ $filtros->cod_ie }}</td>
            @endisset
            @isset( $filtros->sector )
                <th class="align-middle bg-light text-left" width="8%">SECTOR:</th>
                <td class="align-middle text-left">{{ $filtros->sector }}</td>
            @endisset
        </tr>
        <tr>
            <th class="align-middle bg-light text-left" width="8%">CURSO:</th>
            <td class="align-middle text-left">{{ $filtros->curso }}</td>
            @isset( $filtros->distrito )
                <th class="align-middle bg-light text-left" width="8%">DISTRITO:</th>
                <td class="align-middle text-left">{{ $filtros->distrito }}</td>
            @endisset
            @isset( $filtros->zona )
                <th class="align-middle bg-light text-left" width="8%">ZONA:</th>
                <td class="align-middle text-left">{{ $filtros->zona }}</td>
            @endisset
        </tr>
        <tr>
            <th class="align-middle bg-light text-left" width="8%">GRADO:</th>
            <td class="align-middle text-left">{{ $filtros->grado }}</td>
            @isset( $filtros->seccion )
                <th class="align-middle bg-light text-left" width="8%">SECCION:</th>
                <td class="align-middle text-left">{{ $filtros->seccion }}</td>
            @endisset
            @isset( $filtros->sexo )
                <th class="align-middle bg-light text-left" width="8%">SEXO:</th>
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
            <th class="align-middle bg-light text-center" width="3%">#</th>
            <th class="align-middle bg-light text-center" width="8%">I.E.</th>
            <th class="align-middle bg-light text-center" width="8%">DISTRITO</th>
            <th class="align-middle bg-light text-center font-xs" width="4%">SECCIÓN</th>
            <th class="align-middle bg-light text-center" width="10%">ESTUDIANTE</th>
            @for ($pregunta = 1; $pregunta <= $nro_preguntas; $pregunta++ )
                <th class="align-middle bg-light text-center">{{ $pregunta }}</th>
            @endfor
            <th class="align-middle bg-light text-center font-xs" width="4%">ACIERTOS</th>
            <th class="align-middle bg-light text-center font-xs" width="4%">DESACIERTOS</th>
            <th class="align-middle bg-light text-center font-xs" width="4%">BLANCOS</th>
            <th class="align-middle bg-light text-center" width="8%">NIVEL</th>
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

<table class="table-aside table-bordered table-condensed table-sm py-4 text-right font-lg">
    <thead>
        <tr>
            <th class="bg-light text-center" colspan="2">RESUMEN DE NIVELES DE LOGRO</th>
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
        <th class="align-middle bg-light text-center" width="60%">TOTAL ESTUDIANTES</th>
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
            <th class="font-lg bg-light text-center" colspan="8">RESULTADOS SEGÚN DESEMPEÑOS</th>
        </tr>
        <tr>
            <th class="align-middle bg-light text-center" width="7%">PREGUNTA</th>
            <th class="align-middle bg-light text-center" width="15%">COMPETENCIA</th>
            <th class="align-middle bg-light text-center" width="20%">CAPACIDAD</th>
            <th class="align-middle bg-light text-center" width="24%">DESEMPEÑO</th>
            <th class="align-middle bg-light text-center" width="7%">ACIERTOS</th>
            <th class="align-middle bg-light text-center" width="10%">DESACIERTOS</th>
            <th class="align-middle bg-light text-center" width="7%">% DE ACIERTOS</th>
            <th class="align-middle bg-light text-center" width="10%">% DE DESACIERTOS</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $matriz as $item )
            <tr>
                <td class="align-middle text-center">{{ $item->pregunta_nro }}</td>
                <td class="align-middle text-left">{{ $item->competencia }}</td>
                <td class="align-middle text-left">{{ $item->capacidad }}</td>
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