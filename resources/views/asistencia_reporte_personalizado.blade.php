<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Asitencia</title>
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
    <div style="text-align:center;font-size:12px;font-weight:900;">REPORTE DE ASISTENCIA</div>
    <main>
    
        @foreach ($respuesta as $lista)
            <div class=container>
                <aside>
                    <div>Docente : {{$docente}}</div>
                    <div>Cod. Modular/I.E. : {{$modular}}</div>
                    <div>DRE/UGEL : {{$dre}}</div>
                    <div>AÑO : {{$year}}</div>
                    <div>MES : {{$lista["mes_calendario"]}}</div>
                    <div>FECHA DE REPORTE : {{$fecha_reporte}}</div>
                    <div>FECHA DE CIERRE : {{$fecha_cierre}}</div>
                </aside>
                <aside>
                    <div>Gestion : Publica</div>
                    <div>Nivel : {{$nivel}}</div>
                    <div>Ciclo - Grado : {{$ciclo}} - {{$grado}}</div>
                    <div>Seccion - Turno : {{$seccion}} - {{$turno}}</div>
                    <div>Cerrado por :</div>
                </aside>
            </div>
            <table>
                <tr>
                    <th style="border:1px solid black;margin:0;padding:7px;"></th>
                    <th style="border:1px solid black;margin:0;padding:7px;"></th>
                    @for ($i = 1; $i <= $lista["ultimo_dia"]; $i++)
                    <th style="border:1px solid black">{{$i}}</th>
                    @endfor
                </tr>
                <tr>
                    <th style="border:1px solid black;margin:0;padding:7px;">N°</th>
                    <th style="border:1px solid black;margin:0;padding:7px;">NOMBRES Y APELLIDOS</th>
                    @for ($i = 0; $i < $lista["ultimo_dia"]; $i++)
                    <th style="border:1px solid black">{{$dias[($lista["dia"]+$i)%7]}}</th>
                    @endfor
                </tr>
                @foreach ($lista["nombre"] as $indice => $nombre)
                    <tr>
                        <th style="border:1px solid black;margin:0;padding:7px;">{{($indice+1)}}</th>
                        <th style="border:1px solid black;margin:0;padding:7px;">{{$nombre}}</th>
                        @foreach ($lista["asistido"][$indice] as $asistido)
                        <td style="text-align:center;border:1px solid black;margin:0;padding:7px;">{{$asistido}}</td>
                        @endforeach
                    </tr>
                @endforeach
                {{-- @foreach ($lista["asistido"] as $asistencia)
                    
                    <th style="border:1px solid black;margin:0;padding:7px;">{{$asistencia}}</th>
                    
                @endforeach --}}
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
        @endforeach
    
    </main>
    <footer>
        <table>
            <tr>
                <td>1/1</td>
                <td>Autor: --</td>
                <td>12-12-12</td>
            </tr>
        </table>
    </footer>
</body>
</html>