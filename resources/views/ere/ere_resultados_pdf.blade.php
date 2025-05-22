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
            <th class="align-middle bg-light text-left" width="8%">EVALUACIÓN:</th>
            <td class="align-middle text-left">{{ $filtros->evaluacion ?? '' }}</td>
            @isset( $filtros->cod_ie )
                <th class="align-middle bg-light text-left" width="8%">I.E.:</th>
                <td class="align-middle text-left">{{ $filtros->cod_ie }}</td>
            @endisset
            @isset( $filtros->sector )
                <th class="align-middle bg-light text-left" width="8%">GESTIÓN:</th>
                <td class="align-middle text-left">{{ $filtros->sector }}</td>
            @endisset
        </tr>
        <tr>
            <th class="align-middle bg-light text-left" width="8%">ÁREA:</th>
            <td class="align-middle text-left">{{ $filtros->curso ?? '' }}</td>
            @if( isset($filtros->ugel) || isset($filtros->distrito) )
                <th class="align-middle bg-light text-left" width="8%">UGEL/DISTRITO:</th>
                <td class="align-middle text-left">{{ $filtros->ugel ?? '' }} - {{ $filtros->distrito ?? '' }}</td>
            @endif
            @isset( $filtros->zona )
                <th class="align-middle bg-light text-left" width="8%">ZONA:</th>
                <td class="align-middle text-left">{{ $filtros->zona }}</td>
            @endisset
        </tr>
        <tr>
            <th class="align-middle bg-light text-left" width="8%">NIVEL/GRADO:</th>
            <td class="align-middle text-left">{{ $filtros->nivel ?? '' }} - {{ $filtros->grado ?? '' }}</td>
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
            <th class="font-lg bg-light text-center" colspan="{{ $nro_preguntas + $otras_columnas }}">RESULTADOS DE ESTUDIANTES</th>
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
        @foreach ( $niveles as $nivel )
            <tr>
                <td class="align-middle text-left">{{ $nivel->nivel_logro }}</td>
                <td class="align-middle text-center">{{ $nivel->cantidad }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <th class="align-middle bg-light text-center" width="60%">TOTAL ESTUDIANTES</th>
        <th class="align-middle bg-light text-center" width="60%">{{ count($resultados) }}</th>
    </tfoot>
</table>

@if( $filtros->tipo_reporte == 'IE' )
    <div class="page-break"></div>
    <table class="table table-bordered table-condensed table-sm py-4">
        <thead>
            <tr>
                @php( $otras_columnas = 5 );
                <th class="font-lg bg-light text-center" colspan="{{ count($niveles) + $otras_columnas }}">RESULTADOS AGRUPADOS POR IE</th>
            </tr>
            <tr>
                <th class="align-middle bg-light text-center" width="3%">#</th>
                    <th class="align-middle bg-light text-center" width="20%">IE</th>
                    <th class="align-middle bg-light text-center" width="15%">UGEL</th>
                    <th class="align-middle bg-light text-center" width="15%">DISTRITO</th>
                    <th class="align-middle bg-light text-center" width="8%">TOTAL</th>
                @foreach ($niveles as $nivel)
                    <th class="align-middle bg-light text-center">% {{ $nivel->nivel_logro }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ( $ies as $ie )
                <tr>
                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                    <td class="align-middle text-left">{{ $ie->agrupado }}</td>
                    <td class="align-middle text-left">{{ $ie->ugel }}</td>
                    <td class="align-middle text-left">{{ $ie->distrito }}</td>
                    <td class="align-middle text-center">{{ $ie->total }}</td>
                    @foreach ($niveles as $nivel)
                        @php( $nivel_logro_id = $nivel->nivel_logro_id . '' )
                        <td class="align-middle text-center">{{ number_format( intval($ie?->$nivel_logro_id ?? 0), 2) }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

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