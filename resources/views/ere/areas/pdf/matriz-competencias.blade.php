<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">-->
</head>
<style>
    @page {
        font-size: 1em;
    }

    table {
        width: 100%;
        border-spacing: 0;
        border-collapse: collapse;

    }

    td, th {
        font-size: 14px;
        padding: 3px;
        vertical-align: middle;
    }

    table.matriz tbody td {
        border: 1px solid black;
    }

    table.matriz thead th {
        border: 1px solid black;
        text-align: center;
        background-color: #b5b5b5;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    table.matriz tbody tr:nth-child(odd) td {
        background-color: #dfdfdf;
    }

    .center {
        text-align: center;
    }

    .titulo {
        font-size: 18px;
        font-weight: 900;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }

    .logo {
        width: 50px;
        height: auto;
    }

    .logo-derecha {
        position: fixed;
        height: 30px;
        top: -16;
        left: 0cm;
        right: 0cm;
        text-align: right;
        line-height: -2px;
    }

    .logo-izquierda {
        position: fixed;
        height: 30px;
        top: -19;
        left: 0cm;
        right: 0cm;
        text-align: left;
        line-height: -2px;
    }
</style>

<body>
    <main>
        <div class="titulo center">
            MATRIZ DE EVALUACIÓN DE {{ strtoupper($evaluacion->cEvaluacionNombre) }} - {{ strtoupper($evaluacion->cNivelEvalNombre) }}</div>
        <div class="titulo center">{{ strtoupper($area->cCursoNombre) }} {{ strtoupper($area->cGradoAbreviacion) }}
            {{ strtoupper(str_replace('Educación ', '', $area->cNivelTipoNombre)) }}</div>
        <br>
        <br>
        <table class="matriz">
            <thead>
                <tr>
                    <th style="width: 3%">Nº</th>
                    <th style="width: 20%">COMPETENCIA</th>
                    <th style="width: 25%">CAPACIDAD</th>
                    <th>DESEMPEÑO</th>
                    <th style="width: 6%">NIVEL</th>
                    <th style="width: 6%">CLAVE</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $item = 1;
                @endphp
                @foreach ($dataMatriz as $fila)
                    <tr>
                        <td class="center">{{ $item++ }}</td>
                        <td>{{ $fila->cCompetenciaNombre }}</td>
                        <td>{{ $fila->cCapacidadNombre }}</td>
                        <td>{!! $fila->cDesempenoDescripcion !!}</td>
                        <td class="center">{{ $fila->iPreguntaPeso }}</td>
                        <td class="center">{{ strtoupper($fila->cAlternativaLetra) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</body>

</html>
