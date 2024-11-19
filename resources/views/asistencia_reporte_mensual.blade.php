<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Mensual de Asitencia</title>
</head>
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
<body>
    <div style="text-align:center;font-size:12px;font-weight:900;">REPORTE DE ASISTENCIA MENSUAL</div>
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
            <th style="border:1px solid black;margin:0;padding:7px;">-</th>
            @for ($i = 1; $i <= $ultimodia; $i++)
                <th style="border:1px solid black">{{$i}}</th>
            @endfor
            <th style="border:1px solid black"></th>
            <th style="border:1px solid black"></th>
            <th style="border:1px solid black"></th>
            <th style="border:1px solid black"></th>
            <th style="border:1px solid black"></th>
            <th style="border:1px solid black"></th>
        </tr>
        <tr>
            <th style="border:1px solid black;margin:0;padding:7px;">NOMBRES Y APELLIDOS</th>
            @for ($i = 1; $i <= $ultimodia; $i++)
                <th style="border:1px solid black">{{ $dias_Semana[($i+6)%7] }}</th>
            @endfor
            <th style="border:1px solid black">X</th>
            <th style="border:1px solid black">I</th>
            <th style="border:1px solid black">J</th>
            <th style="border:1px solid black">T</th>
            <th style="border:1px solid black">P</th>
            <th style="border:1px solid black">-</th>
        </tr>
        @foreach ($query as $list)
            <tr>
                <th style="border:1px solid black;margin:0;padding:7px;">{{$list->completoalumno}}</th>
                @foreach (json_decode(json_encode($list->diasAsistencia)) as $simbolo)
                    <td style="text-align:center;border:1px solid black;margin:0;padding:7px;">{{$simbolo->cTipoAsiLetra}}</td>
                @endforeach
                <td style="text-align:center;border:1px solid black;margin:0;padding:7px;">{{$list->asistencias}}</td>
                <td style="text-align:center;border:1px solid black;margin:0;padding:7px;">{{$list->inasistencia}}</td>
                <td style="text-align:center;border:1px solid black;margin:0;padding:7px;">{{$list->inasistenciaJustificada}}</td>
                <td style="text-align:center;border:1px solid black;margin:0;padding:7px;">{{$list->tardanzas}}</td>
                <td style="text-align:center;border:1px solid black;margin:0;padding:7px;">{{$list->tardanzaJustificada}}</td>
                <td style="text-align:center;border:1px solid black;margin:0;padding:7px;">{{$list->SinRegistrar}}</td>
            </tr>
        @endforeach
    </table>
    <table>
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
    <footer>
        --
    </footer>
</body>
</html>