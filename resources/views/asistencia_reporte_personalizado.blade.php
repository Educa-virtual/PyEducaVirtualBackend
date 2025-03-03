<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Asitencia</title>
</head>
<style>
    @page {
            font-size: 1em;
    }
    html {
        -webkit-print-color-adjust: exact;
    }
    body{
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        margin: 0;
    }
    main{
        flex: 1;
        margin-left: 45px;
        margin-right: 45px;
    }
    div{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 11px;
    }
    .cuerpo {
        width: 100%;
        border-spacing: 0;
        border-collapse: collapse;
    }
    .cuerpo tr th{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        background-color: #dbdbdb;
        font-size: 10px;
        margin: 0;
    }
    .cuerpo tr td{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 9px;
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
    footer{
        width: 100%;
        font-size: 12px;
    }
    .titulo{
        text-align:center;
        font-size:12px;
        font-weight:900;
        margin-top:30px;
    }
    .tablas{
        table-layout: fixed;
        width: 100%;
    }
    .tablas tr td{
        text-align: center;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 9px;
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
        margin: 0;
    }
    td{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 10px;
        margin: 0;
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
            </tr>
        </table>
    </header>
        @foreach ($respuesta as $index => $lista)
        <main>
            <div class="titulo">REPORTE DE ASISTENCIA</div>
            <table>
                <tr>
                    <td>Docente : {{$docente}}</td><td>Gestion :</td>
                </tr>
                <tr>
                    <td>Cod. Modular/I.E. : {{$modular}}</td><td>Nivel : {{$nivel}}</td>
                </tr>
                <tr>
                    <td>DRE/UGEL : {{$dre}}</td><td>Fase / Periodo:</td>
                </tr>
                <tr>
                    <td>AÑO : {{$year}}</td><td>Ciclo - Grado : {{$ciclo}} - {{$grado}}</td>
                </tr>
                <tr>
                    <td>MES : {{$mes}}</td><td>Seccion - Turno : {{$seccion}} - {{$turno}}</td>
                </tr>
                <tr>
                    <td>FECHA DE REPORTE : {{$fecha_reporte}}</td><td>Cerrado por :</td>
                </tr>
                <tr>
                    <td>FECHA DE CIERRE : {{$fecha_cierre}}</td>
                </tr>
            </table>
            <table class="cuerpo">
                <tr>
                    <th rowspan="3" style="border:1px solid black;margin:0;padding:7px;">N°</th>
                    <th rowspan="3" style="border:1px solid black;margin:0;padding:7px;">NOMBRES Y APELLIDOS</th>
                    <th colspan="{{$lista["ultimo_dia"]}}" style="border:1px solid black;margin:0;padding:7px;">{{ strtoupper($lista["mes_calendario"]) }}</th>
                </tr>
                <tr>
                    
                    @for ($i = 1; $i <= $lista["ultimo_dia"]; $i++)
                    <th style="border:1px solid black">{{$i}}</th>
                    @endfor
                </tr>
                <tr>
                    
                    @for ($i = 0; $i < $lista["ultimo_dia"]; $i++)
                    <th style="border:1px solid black">{{$dias[($lista["dia"]+$i)%7]}}</th>
                    @endfor
                </tr>
                @foreach ($lista["nombre"] as $indice => $nombre)
                    <tr>
                        <th style="border:1px solid black;margin:0;padding:7px;">{{($indice+1)}}</th>
                        <th style="border:1px solid black;margin:0;padding:7px;">{{$nombre}}</th>
                        @foreach ($lista["asistido"][$indice] as $asistido)
                            @if ($asistido!="")
                                <td style="text-align:center;border:1px solid black;margin:0;padding:7px;">{{$asistido}}</td>
                            @else
                                <td style="text-align:center;border:1px solid black;background-color: #dbdbdb;margin:0;padding:7px;">{{$asistido}}</td>
                            @endif
                        @endforeach
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
          
        @endforeach
        
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