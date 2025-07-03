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
        size: A4 landscape;
        font-size: 1em;
    }
    body {
        /*margin: 3cm 2cm 2cm; vertical*/
        margin: 3.5cm 0cm 2cm 0cm;
    }
   
    div{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }

    header table {
        width: 100%;
        border-spacing: 0px; /* Espaciado entre celdas dentro de la tabla */
        border-collapse: collapse; /* Asegura que los bordes no se superpongan */
        margin-top:2px;  /*Deja espacio para la cabecera */
        /* margin-bottom: 10px; Espacio entre tablas */
    }
    header table tr th{
        border:1px solid black;
        padding:5px;
        text-align:left;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        background-color: #d1d1df;
        font-size: 10px;
        margin: 0;
    }
    header table tr td{
        border:1px solid black;
        padding:5px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 10px;
        margin: 0;
    }
  
    aside{
        width: 45%;
        font-size: 10px;
    }
   
    header{
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 2cm; /* Altura fija para la cabecera */
        text-align: center;
        font-size: 14px;
    }
    main{
        position: relative;
        width: 100%;
        font-size: 12px;
        left: 0;
        right: 0;
        top: 20px;
    }
    footer{
        position: fixed;
        width: 100%;
        left: 0;
        right: 0;
        bottom: 0cm;
        text-align: center;
        font-size: 14px;
        height: 1cm;
    }
    main table{
        width: 100%;
        border-spacing: 0px; /* Espaciado entre celdas dentro de la tabla */
        border-collapse: collapse; /* Asegura que los bordes no se superpongan */
        margin-top:2px;  /*Deja espacio para la cabecera */
    }
    main table tr th{
        border:1px solid black;
        padding:5px;
        text-align:center;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        background-color: #d1d1df;
        font-size: 10px;
        margin: 0;
    }
    main table tr td{
        border:1px solid black;
        padding:5px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 10px;
        margin: 0;
    }
    footer table{
        width: 100%;
        border-spacing: 0px; /* Espaciado entre celdas dentro de la tabla */
        border-collapse: collapse; /* Asegura que los bordes no se superpongan */
        margin-top:2px;  /*Deja espacio para la cabecera */
    }
    footer table tr th{
        border:1px solid black;
        padding:5px;
        text-align:center;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        background-color: #d1d1df;
        font-size: 10px;
        margin: 0;
    }
    .table-flotante-izquierda {
        margin-top:2px;
        width: 45%; /* Ajusta el ancho de las tablas */
       /*  float: left; Permite que las tablas estén una al lado de la otra */
        margin-right: 10px; /* Espacio entre las tablas */
        border-spacing: 5px;
        border-collapse: collapse;
    }
    .table-flotante-derecha {
        border: 1px solid black; /* Bordes externos */
        margin-top:2px;
        width: 45%; /* Ajusta el ancho de las tablas */
        float: right; /*Permite que las tablas estén una al lado de la otra */
        margin-left: 10px; /* Espacio entre las tablas */
        border-spacing: 5px;
        border-collapse: collapse;
  }

    .table-flotante-derecha td {
        border-right: 1px solid black;
        padding: 5px;
        text-align: center;
        text-align:left;
    }

    .table-flotante-derecha td:last-child {
        border-right: none; /* Elimina el borde a la última columna */
    }
    .cabecera-table{ 
        border:1px solid black; 
        text-align:center;
        background-color: #dbdbdb;
    }
    .logo-derecha { 
        position: fixed;
        height: 30px;
        top: 0;
        left: 0cm;
        right: 0cm;
        text-align: right;
        line-height: -2px;
 }
    .logo-izquierda {
        position: fixed;
        height: 30px;
        top: 0;
        left: 0cm;
        right: 0cm;
        text-align: left;
        line-height: -2px;
    }
    .titulo{
        margin-top: 50px; /* Deja espacio para la cabecera */
        width: 100%; /* Ancho del contenedor */
        text-align:center;
        height: auto; /* Ajusta la altura automáticamente según el contenido */
        font-size:18px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        line-height: 1.2; /* Espaciado reducido entre líneas */
       /* border: 1px solid red;  Opcional: añade un borde para visualización */
        overflow-wrap: break-word; /* Asegura que el texto se ajuste al ancho */
        word-wrap: break-word; /* Compatibilidad para navegadores más antiguos */
        white-space: normal; /* Permite múltiples líneas */
    }
    .header-titulo {
        position: fixed;
        top: 0; /* Ubica el contenedor en la parte superior */
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
  
    /* Marca de agua */
    .marca-agua {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 100%; /* Cubrir todo el ancho */
           
            text-align: center;
            font-size: 50%; /* Aumentar el tamaño del texto */
            opacity: 0.1;
            color: rgba(0, 0, 0, 0.8); /* Transparente */
            transform: translate(-50%, -50%) ;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
    }
</style>
<body>

    <header>
        <div class="logo-izquierda">
            <img src="{{$logo}}" style="height: 50px;">
        </div>
        <div class="logo-derecha">
            <img src="{{$logo}}" style="height: 50px;" >
        </div>
        <div class="titulo"><u>REPORTE DE ASISTENCIA MENSUAL</u> </div>  
        <table>
            <tr>
                <th>Docente :</th>
                <td>{{$docente}} </td>
                <th>Institución Educativa :</th>
                <td>{{$iiee}} </td>
                <th>Mes del Reporte :</th>
                <td>{{$mes}} </td>
            </tr>
            <tr>
                <th>Área curricular :</th>
                <td>{{$area_curricular}} </td>
                <th>Cod. Modular :</th>
                <td>{{$modular}} </td>
                <th>Nivel Educativo :</th>
                <td>{{$nivel}}</td>
                
            </tr>
            <tr>
                <th>Grado / Seccion :</th>
                <td>{{$grado}} - {{$seccion}}</td>
                <th>Ciclo :</th>
                <td>{{$ciclo}}</td>
                <th>Fecha Inicio - Fin :</th>
                <td>{{$inicio}} - {{$fin}}</td>
            </tr>
        </table>   
    </header>
    <main>
        <table>
            <tr>
                <th></th>
                <th></th>
                @for ($i = 1; $i <= $ultimodia; $i++)
                    <th>{{$i}}</th>
                @endfor
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th>N°</th>
                <th>NOMBRES Y APELLIDOS</th>
                @for ($i = 1; $i <= $ultimodia; $i++)
                    <th>{{ $dias_Semana[($i+6)%7] }}</th>
                @endfor
                <th>X</th>
                <th>I</th>
                <th>J</th>
                <th>T</th>
                <th>P</th>
                <th>-</th>
            </tr>
            @foreach ($query as $list)
                <tr>
                    <th>{{($loop->index)+1}}</th> 
                    <th>{{strtoupper($list["completoalumno"])}}</th>
                    @foreach ($list["diasAsistencia"] as $simbolo)
                        <td>{{$simbolo["cTipoAsiLetra"]}}</td>
                    @endforeach
                    <td>{{$list["asistencias"]}}</td>
                    <td>{{$list["inasistencia"]}}</td>
                    <td>{{$list["inasistenciaJustificada"]}}</td>
                    <td>{{$list["tardanzas"]}}</td>
                    <td>{{$list["tardanzaJustificada"]}}</td>
                    <td>{{$list["SinRegistrar"]}}</td>
                </tr>
            @endforeach
        </table>
    </main>
    <footer>
        <table>
            <tr>
                <th>Legenda:</th>
                <th>[X] Asistio</th>
                <th>[I] Inasistencia</th>
                <th>[J] Inasistencia Justificada</th>
                <th>[T] Tardanza</th>
                <th>[P] Tardanza Justificada</th>
                <th>[-] Sin Registro</th>
            </tr>
        </table>
    </footer>

    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Verdana, Geneva, Tahoma, sans-serif", "normal");
                $pdf->text(70, 570, "Página $PAGE_NUM de $PAGE_COUNT", $font, 10);
                $pdf->text(670, 570, date("Y-m-d H:m:s"), $font, 10);
            ');
        }
    </script>
</body>
</html>