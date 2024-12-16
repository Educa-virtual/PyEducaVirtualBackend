<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        background-color: #dbdbdb;
        font-size: 10px;
        margin: 2;
    }
    td{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 10px;
        margin: 2;
        text-align: center;
    }
    .logro{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 10px;
        margin: 2;
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
        font-size:12px;
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
    .id{
        width: 30px;
        text-align: center;
    }
</style>
<body>

    <header class="table-header">
        <table style="width: 100%; text-align: center;">
            <tr>
            <td style="width: 20%; text-align: center;">
                <img src="logo1.png" alt="Logo 1" style="width: 80px; height: auto;">
            </td>
            <td style="width: 60%; text-align: center;">
                "Año del Bicentenario, de la consolidación de nuestra Independencia, y de la conmemoración de las heroicas batallas de
                Junín y Ayacucho"
            </td>
            <td style="width: 20%; text-align: center;">
                <img src="./img/Logo-buho.png" alt="Logo 2" style="width: 10px; height: auto;">
            </td>
            </tr>
        </table>
    </header>
    <br>
    <br>
    <div class="titulo" >REPORTE DE LOGRO DE APRENDIZAJE</div>
    <div class="titulo" >Curso: </div>
    <main>
        <div class="container">
            <aside>
                <div>Cod-M : </div>
                <div>Docente : </div>                
                <div>Año : </div>
                <div>Nivel Educativo : </div>
            </aside>
            <aside>
                <div>Sección - Turno :</div>
                <div>Ciclo - Grado : </div>
                <div>Fase / Periodo:</div>
            </aside>
        </div>
        <table border="1" class="cuerpo">
            <tr>
                <th rowspan="2">Nro</th>
                <th rowspan="2">Nombres Y Apellidos</th>
                <th colspan="5">AÑO 2024</th>                
            </tr>
            <tr>
                <th>TRIM I</th>
                <th>TRIM II</th>
                <th>TRIM III</th>
                <th>TRIM IV</th>
                <th>CONCLUSIÓN DESCRIPTIVA</th>
            </tr>
            <tbody>
                @php
                $item = 1; 
                @endphp
                @foreach($preguntas as $pregunta) <!-- Cambié $respuesta['preguntas'] por $preguntas -->
                    <tr>
                        <td>{{ $item++ }}</td>
                        <td>{{ $pregunta['completoalumno'] }}</td>
                        <td>{{ $pregunta['Trimestre_I']}}</td>
                        <td>{{ $pregunta['Trimestre_II']}}</td>
                        <td>{{ $pregunta['Trimestre_III']}}</td>
                        <td>{{ $pregunta['Trimestre_IV']}}</td>
                        <td>{{$pregunta['Conclusion_descriptiva']}} </td>
                        
                    </tr>
                @endforeach
            </tbody>            
        </table>        
        <br><br>
        <table>
            <tr>
                <td>Legenda:</td>
                <td>[AD] Logro destacado</td>
                <td>[A] Logro esperado</td>
                <td>[B] En Proceso</td>
                <td>[C] En Inicio</td>
            </tr>
        </table>
    </main>    
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