<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Mensual de Asitencia</title>
</head>
<style>
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
        font-size: 12px;
        margin: 0;
    }
    td{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 12px;
        margin: 0;
    }
    .container{
        display: flex;
        justify-content: space-between;
        padding: 20px;
    }
    aside{
        width: 45%;
        padding: 10px;
    }
</style>
<body>
    <div style="text-align: center;font-size:16px;font-weight:900;">REPORTE DE ASISTENCIA MENSUAL</div>
    <div class=container>
        <aside>
            <div>Cod. Modular/I.E. :</div>
            <div>DRE/UGEL :</div>
            <div>AÑO :</div>
            <div>MES :</div>
            <div>FECHA DE REPORTE :</div>
            <div>FECHA DE CIERRE :</div>
        </aside>
        <aside>
            <div>Gestion :</div>
            <div>Nivel :</div>
            <div>Fase / Periodo:</div>
            <div>Ciclo - Grado :</div>
            <div>Seccion - Turno :</div>
            <div>Cerrado por :</div>
        </aside>
    </div>
    <table>
        <tr>
            <th style="border:1px solid black;margin:0;padding:7px;">-</th>
            @for ($i = 1; $i <= $ultimodia; $i++)
                <th style="border:1px solid black">{{$i}}</th>
            @endfor
        </tr>
        <tr>
            <th style="border:1px solid black;margin:0;padding:7px;">NOMBRES Y APELLIDOS</th>
            @for ($i = 1; $i <= $ultimodia; $i++)
                <th style="border:1px solid black">{{ $dias_Semana[($i+6)%7] }}</th>
            @endfor
        </tr>
        @foreach ($query as $list)
            <tr>
                <th style="border:1px solid black;margin:0;padding:7px;">{{$list->nombrecompleto}}</th>
                @foreach (json_decode(json_encode($list->diasAsistencia)) as $simbolo)
                    <td style="text-align:center;border:1px solid black;margin:0;padding:7px;">{{$simbolo->cTipoAsiLetra}}</td>
                @endforeach
            </tr>
        @endforeach
    </table>
</body>
</html>