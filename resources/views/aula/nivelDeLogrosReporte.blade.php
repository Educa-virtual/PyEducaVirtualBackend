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
    <main>
    <div class="titulo">REPORTE DE LOGRO DE APRENDIZAJE</div>
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
    
    <!-- <table>
        <tr>
            <td>Legenda:</td>
            <td>[X] Asistio</td>
            <td>[I] Inasistencia</td>
            <td>[J] Inasistencia Justificada</td>
            <td>[T] Tardanza</td>
            <td>[P] Tardanza Justificada</td>
            <td>[-] Sin Registro</td>
        </tr>
    </table> -->
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