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

    thead {
        display: table-header-group;
    }

    table {
        width: 100%;
        border-spacing: 0;
        border-collapse: collapse;
    }

    footer {
        position: fixed;
        bottom: -160px;
        left: 0px;
        right: 0px;
        height: 300px;
        font-style: italic;
        font-size: 10px;
    }

    footer div {
        text-align: justify;
    }

    footer h4 {
        margin-top: 2px;
        margin-bottom: 2px;
    }

    td,
    th {
        font-size: 14px;
        padding: 3px;
        vertical-align: middle;
    }

    table.matriz {
        margin-top: 30px;
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
        position: fixed;
        font-size: 18px;
        font-weight: 900;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        top: 20;
        left: 0cm;
        right: 0cm;
        height: 50px;
        text-align: center;
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
        top: -16;
        left: 0cm;
        right: 0cm;
        text-align: left;
        line-height: -2px;
    }

    .page-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 60px;
    }

    body {
        margin-top: 50px;
        font-family: Arial, sans-serif;
    }

    .texto-anio-oficial {
        position: fixed;
        height: 20px;
        top: -10px;
        left: 0;
        right: 0;
        text-align: center;
        line-height: 10px;
        font-size: 12px;
    }
</style>

<body>
    <div class="page-header">
        <div>
            <div class="logo-izquierda" style="float: left; margin-left: 10px;">
                <img src="{{ public_path('images/logo_IE/dremo.jpg') }}" style="height: 50px;">
            </div>
            <div class="texto-anio-oficial">
                {{ $year->cYearOficial }}
                <br>
            </div>
            <div class="logo-derecha" style="float: right; margin-right: 10px;">
                <img src="{{ public_path('images/logo_IE/Logo-buho.jpg') }}" style="height: 40px;">
            </div>
        </div>

    </div>

    <main>
        <div class="titulo">
            <div>
                MATRIZ DE EVALUACIÓN DE {{ strtoupper($evaluacion->cEvaluacionNombre) }} -
                {{ strtoupper($evaluacion->cNivelEvalNombre) }}</div>
            <div>{{ strtoupper($area->cCursoNombre) }} {{ strtoupper($area->cGradoAbreviacion) }}
                {{ strtoupper(str_replace('Educación ', '', $area->cNivelTipoNombre)) }}</div>
        </div>

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

        <footer>
            <header>
                <h4>Nota de Confidencialidad:</h4>
            </header>
            <div>Este documento es estrictamente confidencial y está destinado exclusivamente para el uso del docente y
                el
                personal autorizado.
                Conforme a la Ley N° 29733, Ley de Protección de Datos Personales, queda prohibida su reproducción,
                distribución o utilización
                con fines distintos a los educativos establecidos, protegiendo así la privacidad de alumnos y docentes.
            </div>
        </footer>
    </main>

    <script type="text/php">
        if (isset($pdf)) {
                $nombrePersona = "Autor: {{ strtolower($persona->cPersNombre) }} {{ strtolower($persona->cPersPaterno) }} {{ strtolower($persona->cPersMaterno) }}";
                $font = null;
                $size = 10;
                $color = array(0, 0, 0);
                //Numero de pagina
                $pdf->page_text(30, 540, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, $size, $color, 0.0, 0.0, 0.0);
                //Persona
                $pdf->page_text(320, 540, ucwords($nombrePersona), $font, $size, $color, 0.0, 0.0, 0.0);
                //Fecha
                $pdf->page_text(715, 540, date("d/m/Y H:i:s"), $font, $size, $color, 0.0, 0.0, 0.0);
            }
    </script>
</body>

</html>
