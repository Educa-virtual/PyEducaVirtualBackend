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
        margin-left: 45px;
        margin-right: 45px;
    }
    div{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 11px;
    }
    table {
        width: 100%;
        border-spacing: 0;
        border-collapse: collapse;
    }
    th{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        background-color: #dbdbdb;
        font-size: 11px;
        margin: 0;
    }
    td{
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
        margin-bottom: 10px;
        margin-left: 10px;
    }
    .titulo{
        text-align:center;
        font-size:12px;
        font-weight:900;
        margin-top:30px;
    
    }
    .pie{
        width: 100%;
    }
    .pie>tr{
        width: 100%;
    }
    .pie>tr>td{
        text-align: center;
    }
</style>
<body>
    <div class="titulo">REPORTE DE ASISTENCIA</div>
    <main>
    
        @foreach ($respuesta as $lista)
            <div class=container>
                <aside>
                    <div>Docente : {{$docente}}</div>
                    <div>Cod. Modular/I.E. : {{$modular}}</div>
                    <div>DRE/UGEL : {{$dre}}</div>
                    <div>AÑO : {{$year}}</div>
                    <div>MES : {{ strtoupper($lista["mes_calendario"]) }}</div>
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
        <div>
            <p>Nota de Confidencialidad:</p>
            <p>
                Este reporte de asistencia es estrictamente confidencial y está destinado exclusivamente para el uso del docente y el personal autorizado. Conforme a la Ley N° 29733,
                Ley de Protección de Datos Personales, queda prohibida su reproducción, distribución o utilización con fines distintos a los educativos establecidos, protegiendo así la
                privacidad de alumnos y docentes
            </p>
        </div>
    </main>
    <footer>
        <table class="pie">
            <tr>
                <td>1/1</td>
                <td>Autor: --</td>
                <td>{{$fecha_reporte}}</td>
            </tr>
        </table>
    </footer>
</body>
</html>