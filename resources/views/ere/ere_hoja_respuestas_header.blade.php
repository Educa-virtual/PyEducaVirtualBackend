@extends('layouts.pdf')

@section('title', 'HOJA DE RESPUESTAS')

@section('content')

<style>
    @page {
        margin-top: -3cm;
        margin-bottom: 1.5cm;
        margin-left: 1.5cm;
        margin-right: 1.5cm;
    }
</style>

<main class="container-fluid">
    <table class="table table-condensed text-center table-sm py-2">
        <tr>
            <td width="30%" class="text-left align-middle"><img src="{{ public_path('images/logo-dremo.png') }}" width="100%"></td>
            <td width="60%" class="text-center align-middle"></td>
            <td width="10%" class="text-right align-middle"><img src="{{ public_path('images/logo-plataforma-virtual.png') }}" width="100%"></td>
        </tr>
    </table>
</main>


@endsection