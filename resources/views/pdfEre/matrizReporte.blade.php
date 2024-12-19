<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <title>Reporte Mensual de Asitencia</title>
</head>
<style>
    @page {
            font-size: 1em;
    }
    body{
        display: flex;
        flex-direction: column;
        margin-top: 1cm;
    }
    main{
        flex: 1;
        padding: 10px;
    }
    div{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }
    .table-header{
        width: 100%;
    }
    .table-header tr td{
        text-align:center;
        font-size:12px;
    }
    table {
        width: 100%;
        border-spacing: 0;
        border-collapse: collapse;
        
    }
    th{
        /* font-family: Verdana, Geneva, Tahoma, sans-serif; */
        font-family: 'Roboto', sans-serif;
        background-color: #dbdbdb;
        font-size: 14px;
        margin: 0;
        
    }
    td{
        /* font-family: Verdana, Geneva, Tahoma, sans-serif; */
        font-family: 'Roboto', sans-serif;
        font-size: 12px;
        margin: 0;
        padding-bottom: 5px;
      
    }
    .container{
        display: flex;
        justify-content: space-between;
        padding: 20px;
    }
    aside{
        width: 45%;
        font-size: 10px;
    }
    .titulo{
        text-align:center;
        font-size:16px;
        font-weight:900;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }
    header{
        position: fixed;
        height: 2cm;
        top: 0;
        left: 0cm;
        right: 0cm;
        text-align: center;
        line-height: 30px;
    }
    footer{
        position: fixed;
        height: 2cm;
        bottom: 0;
        left: 0cm;
        right: 0cm;
        text-align: center;
        line-height: 30px;
    }
.logo {
            width: 50px; /* Tamaño del logo */
            height: auto; /* Mantiene la proporción del logo */
        }

</style>
<body>

    <header class="table-header">
        <table>
            <tr>
                <td></td>
                <td>
                    "Año del Bicentenario, de la consolidación de nuestra Independencia, y de la conmemoración de las heroicas batallas de
                    Junín y Ayacucho"
                </td>
                <td></td>
                <td>
                    <img src="logo.png" alt="Logo" class="logo">
                </td>
            </tr>
        </table>
    </header>
    <main>


    <div class="titulo">Matriz de la {{ $nombreEvaluacion }}</div>
    <table>

        <tr>
            <td>Nombre de la evaluación: {{$nombreEvaluacion}}  </td>
        </tr>
        <tr>
            <td>Área: {{$nombreCurso}}</td>
        </tr>
        <tr>
            <td>Nivel: {{ $nivel }} </td>
        </tr>
        <tr>
            <td>Grado: {{ $grado }}</td>
        </tr>
        
    </table>
    <br>
    <br>
    <table>
<thead style="text-align: center; border: 1px solid #000;  border-collapse: collapse;">
    <tr>
        <th style="border: 1px solid #000;font-size:14px;">Item</th>
        <th style="border: 1px solid #000;font-size:14px;">Competencia</th>
        <th style="border: 1px solid #000;font-size:14px;">Capacidad</th>
        <th style="border: 1px solid #000;font-size:14px;">Desempeño</th>
        <th style="border: 1px solid #000;font-size:14px;">Nivel</th>
        <th style="border: 1px solid #000;font-size:14px;">Clave</th>
        <th style="border: 1px solid #000;font-size:14px;">Id Pregunta</th>
        <th style="border: 1px solid #000;font-size:14px;">Id Evaluacion</th>
    </tr>
</thead>
<tbody style="text-align: center;border: 1px solid #000; vertical-align: middle; border-collapse: collapse; ">
    @php
    $item = 1; 
    @endphp
    @foreach($preguntas as $pregunta) <!-- Cambié $respuesta['preguntas'] por $preguntas -->
        <tr>
            <td style="border: 1px solid #000 ;font-size:14px;">{{ $item++ }}</td>
            <td style="border: 1px solid #000 ;font-size:14px;">{{ $pregunta['competencia_nombre'] }}</td>
            <td style="border: 1px solid #000 ;font-size:14px;">{{ $pregunta['capacidad_nombre'] }}</td>
            <td style="border: 1px solid #000 ;font-size:14px;">{{ $pregunta['desempeno_descripcion'] }}</td>
            <td style="border: 1px solid #000 ;font-size:14px;">{{ $pregunta['pregunta_nivel'] }}</td>
            <td style="border: 1px solid #000 ;font-size:14px;">{{ $pregunta['pregunta_clave'] }}</td>
            {{-- <td style="border: 1px solid #000 ;font-size:14px;">{{ $pregunta['iPreguntaId'] }}</td>
            <td style="border: 1px solid #000 ;font-size:14px;">{{ $pregunta['iEvaluacionId'] }}</td> --}}
        </tr>
    @endforeach
</tbody>
</table>

    </main>
    
        <p style="font-style: italic;">
        Nota de Confidencialidad:
        Este reporte de asistencia es estrictamente confidencial y está destinado exclusivamente para el uso del docente y el personal autorizado. Conforme a
        la Ley N° 29733, Ley de Protección de Datos Personales, queda prohibida su reproducción, distribución o utilización con fines distintos a los educativos
        establecidos, protegiendo así la privacidad de alumnos y docentes.
    </p>
    
    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Verdana, Geneva, Tahoma, sans-serif", "normal");
                $pdf->text(70, 570, "Página $PAGE_NUM de $PAGE_COUNT", $font, 10);
                $pdf->text(370, 570, "Autor:--", $font, 10);
                $pdf->text(670, 570, date("Y-m-d H:m:s"), $font, 10);
            ');
        }
    </script>
</body>
</html>