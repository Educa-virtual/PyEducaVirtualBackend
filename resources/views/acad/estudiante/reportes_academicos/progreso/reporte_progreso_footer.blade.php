@extends('layouts.pdf')

@section('title', 'RESULTADOS ERE')

@section('content')

<style>
    @page {
        margin-top: 1.5cm;
        margin-bottom: 1.5cm;
        margin-left: 1.5cm;
        margin-right: 1.5cm;
    }
</style>

<main class="container-fluid">
    <table class="table table-borderless table-condensed table-sm py-2">
        <tr>
            <td width="20%" class="text-left"></td>
            <td width="60%" class="text-center">IMPRESO POR AUTOR</td>
            <td width="20%" class="text-right">IMPRESO EL {{ date('d/m/Y') }} A LAS {{ date('h:i') }}</td>
        </tr>
    </table>
</main>
