<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Diario</title>
    <style>
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
    footer{
        font-size: 12px;
        margin-bottom: 10px;
        margin-left: 10px;
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
        
        <table>
            <tr>
                <td style="border:1px solid black;margin:0;padding:7px;">Nombres Y Apellidos</td>
                <td style="border:1px solid black;margin:0;padding:7px;" >{{$fecha_actual}}</td>
            </tr>
            <tr>
                
            </tr>
        </table>
    </main>
</body>
</html>