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
.header-titulo {
        margin-top: -10px;
        position: fixed;
        top: 7px; /* Ubica el contenedor en la parte superior */
        left: 25%; /* Centra horizontalmente desde la izquierda */
        text-align: center;  /* Centrado */
        width: 50%; /* Ancho del contenedor */
        height: auto; /* Ajusta la altura automáticamente según el contenido */
        line-height: -2px;
        font-size: 12px;  /* Tamaño de la fuente */
        font-family: 'Verdana', Geneva, Tahoma, sans-serif;
        line-height: 1.2; /* Espaciado reducido entre líneas */
       /* border: 2px solid #ccc;  Opcional: añade un borde para visualizar mejor */
        overflow-wrap: break-word; /* Asegura que el texto se ajuste al ancho */
        word-wrap: break-word; /* Compatibilidad para navegadores más antiguos */
        white-space: normal; /* Permite múltiples líneas */
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
    .matriz td{
        font-family: 'Roboto', sans-serif;
        
    }
</style>
<body>

    {{-- <header class="table-header">
        <table>
            <tr>
                <td></td>
                <td>
                    "Año del Bicentenario, de la consolidación de nuestra Independencia, y de la conmemoración de las heroicas batallas de
                    Junín y Ayacucho"
                </td>
                <td>  <img src="{{$imageLogo}} " style="height: 1px;"></td>
                <td>
                     <img src="{{$logoVirtual}} " style="height: 50px;" >
                </td>
            </tr>
        </table>
    </header> --}}

<div  class="header-titulo" style="text-align: center;">
        "Año del Bicentenario, de la consolidación de nuestra Independencia, y de la conmemoración de las heroicas batallas de
        Junín y Ayacucho"
        </div>
        
        <div class="logo-izquierda">
            <img src="{{$imageLogo}} " style="height: 60px;">
        </div>
        <div class="logo-derecha">
            <img src="{{$logoVirtual}} " style="height: 50px;" >
        </div>
    <main>


    <div class="titulo">MATRIZ DE LA {{ $nombreEvaluacion }}</div>
    <table class="matriz">
        <tr>
            <td>NOMBRE DE LA EVALUACIÓN: {{ $nombreEvaluacion }}</td>
        </tr>
        <tr>
            <td>ÁREA: {{ $nombreCurso }}</td>
        </tr>
        <tr>
            <td>NIVEL: {{ $nivel }}</td>
        </tr>
        <tr>
            <td>GRADO: {{ $grado }}</td>
        </tr>
        <tr>
            <td>FECHA: {{ $dtCreado }}</td>
        </tr>
    <tr>
            <td>NOMBRE DEL ESPECIALISTA: {{ $especialista }}</td>
        </tr>
        
    </table>
    <br>
    <br>
    <table>
<thead style="text-align: center; border: 1px solid #000;  border-collapse: collapse;">
    <tr>
        <th style="border: 1px solid #000;font-size:14px;">Nº</th>
        <th style="border: 1px solid #000; font-size: 14px;">COMPETENCIA</th>
        <th style="border: 1px solid #000; font-size: 14px;">CAPACIDAD</th>
        <th style="border: 1px solid #000; font-size: 14px;">DESEMPEÑO</th>
        <th style="border: 1px solid #000; font-size: 14px;">NIVEL / PESO</th>
        <th style="border: 1px solid #000; font-size: 14px;">CLAVE</th>
    </tr>
</thead>
<tbody style="text-align: center;border: 1px solid #000; vertical-align: middle; border-collapse: collapse; ">
    @php
    $item = 1; 
    @endphp
    @foreach($preguntas as $pregunta) <!-- Cambié $respuesta['preguntas'] por $preguntas -->
        <tr>
            <td style="border: 1px solid #000; font-size:14px; ">{{ $item++ }}</td>
            <td style="border: 1px solid #000; font-size:14px; ">{{ $pregunta['competencia_nombre'] }}</td>
            <td style="border: 1px solid #000; font-size:14px; ">{{ $pregunta['capacidad_nombre'] }}</td>
            <td style="border: 1px solid #000; font-size:14px; ">{{ $pregunta['desempeno_descripcion'] }}</td>
            <td style="border: 1px solid #000; font-size:14px; ">{{ $pregunta['pregunta_nivel'] }}</td>
            <td style="border: 1px solid #000; font-size:14px; text-transform: uppercase; ">{{ $pregunta['pregunta_clave'] }}</td>
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