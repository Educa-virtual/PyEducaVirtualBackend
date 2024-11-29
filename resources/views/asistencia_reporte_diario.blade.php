<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Diario</title>
    <style>
    .page-break {
    page-break-after: always;
    }
           body{
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        margin: 0;
    }
    main{
        flex: 1;
        padding: 10px;
    }
    div{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
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
        margin: 0;
    }
    td{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 10px;
        margin: 0;
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
    .subtitulo{
        border:1px solid black;
        margin:0;
        padding:7px;
        background-color: #dbdbdb;
        font-weight: 900;
    }
    .celda{
        border:1px solid black;
        margin:0;
        padding:7px;
        background-color: #dbdbdb;
    }
    .contenido{
        border:1px solid black;
        margin:0;
        padding:7px;
    }
    .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #000;
            padding: 5px;
    }
    </style>
    
</head>
<body>
    <div style="text-align:center;font-size:12px;font-weight:900;">REPORTE DE ASISTENCIA</div>
    <main>
        <div class=container>
            <aside>
                <div>Docente : {{$docente}}</div>
                <div>Cod. Modular/I.E. : {{$modular}}</div>
                <div>DRE/UGEL : {{$dre}}</div>
                <div>AÑO : {{$year}}</div>
                <div>MES : {{$mes}}</div>
                <div>FECHA DE REPORTE : {{$fecha_reporte}}</div>
                <div>FECHA DE CIERRE : {{$fecha_cierre}}</div>
            </aside>
            <aside>
                <div>Gestion :</div>
                <div>Nivel : {{$nivel}}</div>
                <div>Fase / Periodo:</div>
                <div>Ciclo - Grado : {{$grado}}</div>
                <div>Seccion - Turno : {{$seccion}} - {{$turno}}</div>
                <div>Cerrado por :</div>
            </aside>
        </div>
        
        <table class="cuerpo">
            <tr>
                <td class="subtitulo">Nro</td>
                <td class="subtitulo">Nombres Y Apellidos</td>
                <td class="subtitulo">{{$fecha_actual}}</td>
            </tr>
            @foreach($respuesta["lista"] as $key => $index)
            <tr>
                <td class="celda">{{$key+1}}</th>
                <td class="celda">{{$index[0]}}</th>
                <td class="contenido">{{$index[1]}}</td>
            </tr>
            @endforeach
            
        </table>
        <table class="cuerpo">
            <tr>
                <td>Legenda:</td>
                <td>[X] Asistio</td>
                <td>[I] Inasistencia</td>
                <td>[J] Inasistencia Justificada</td>
                <td>[T] Tardanza</td>
                <td>[P] Tardanza Justificada</td>
                <td>[-] Sin Registro</td>
            </tr>
        </table>
    </main>
    
    {{-- ver el numero de pagina en pie de pagina --}}
    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->text(270, 820, "Pág $PAGE_NUM de $PAGE_COUNT", $font, 10);
            ');
        }
    </script>
</body>
</html>