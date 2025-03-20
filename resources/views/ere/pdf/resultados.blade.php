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
                <td width="5%" class="align-middle" rowspan="3"><img src="{{ asset('img/logo_bn.png') }}" width="100%"></td>
                <td width="95%" class="text-center align-middle">RESULTADOS ERE</td>
            </tr>
        </table>
    </main>
</header>

<footer class="container-fluid">
    <table class="table table-borderless table-condensed table-sm py-2">
        <tr>
            <td width="80%" class="text-left font-weight-bold">IMPRESO EL {{ date('d/m/Y') }}</td>
            <td width="20%" class="text-right font-weight-bold">PÁGINA <span class="paginacion"></span></td>
        </tr>
    </table>
</footer>

<main class="container-fluid">

<table class="table table-bordered table-condensed table-sm py-4">
    <thead>
        <tr>
            <th class="align-middle bg-light text-left" width="5%">#</th>
            <th class="align-middle bg-light text-left" width="20%">I.E.</th>
            <th class="align-middle bg-light text-left" width="10%">DISTRITO</th>
            <th class="align-middle bg-light text-left" width="5%">SECCION</th>
            <th class="align-middle bg-light text-left" width="35%">ESTUDIANTE</th>
            <th class="align-middle bg-light text-right" width="5%">ACIERTOS</th>
            <th class="align-middle bg-light text-right" width="5%">DESACIERTOS</th>
            <th class="align-middle bg-light text-right" width="5%">BLANCOS</th>
            <th class="align-middle bg-light text-right" width="10%">NIVEL</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $resultados as $resultado )
            <tr>
                <td class="align-middle text-left">{{ $loop->iteration }}</td>
                <td class="align-middle text-left">{{ $resultado->cod_ie }}</td>
                <td class="align-middle text-left">{{ $resultado->distrito }}</td>
                <td class="align-middle text-left">{{ $resultado->seccion }}</td>
                <td class="align-middle text-left">{{ $resultado->estudiante }}</td>
                <td class="align-middle text-right">{{ number_format($resultado->aciertos, 0, '.', ',') }}</td>
                <td class="align-middle text-right">{{ number_format($resultado->desaciertos, 0, '.', ',') }}</td>
                <td class="align-middle text-right">{{ number_format($resultado->blancos, 0, '.', ',') }}</td>
                <td class="align-middle text-right">{{ $resultado->nivel_logro }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</main>

@endsection