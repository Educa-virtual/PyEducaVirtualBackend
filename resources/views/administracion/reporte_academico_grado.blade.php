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
    body {
        margin: 3.5cm 0.5cm 0.5cm;
    }
   
    div{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }

    table {
        width: 100%;
        border-spacing: 0px; /* Espaciado entre celdas dentro de la tabla */
        border-collapse: collapse; /* Asegura que los bordes no se superpongan */
        margin-top:2px;  /*Deja espacio para la cabecera */
        /* margin-bottom: 10px; Espacio entre tablas */
    }
    th{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        background-color: #d1d1df;
        font-size: 10px;
        margin: 0;
        border: 1px solid black;
    }
    td{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 10px;
        margin: 0;
        text-align:center;
        border:1px solid black;
        margin:0;
        padding:5px;
        border: 1px solid black;
    }
  
    aside{
        width: 40%;
        font-size: 10px;
        text-align: left;
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
        font-size: 12px;
    }
    footer{
        position: fixed;
        height: 2cm;
        bottom: 0;
        left: 0cm;
        right: 0cm;
        text-align: center;
        line-height: 35px;
    }
    .table-flotante-izquierda {
        margin-top:2px;
        width: 100%; /* Ajusta el ancho de las tablas */
        float: left;/*  Permite que las tablas estén una al lado de la otra */
        margin-right: 10px; /* Espacio entre las tablas */
        margin-left: 20px;
        border-spacing: 5px;
        border-collapse: collapse;
    }
    .table-flotante-derecha {
        /* border: 1px solid black; Bordes externos */
        margin-top:2px;
        width: 60%; /* Ajusta el ancho de las tablas */
        float: right; /*Permite que las tablas estén una al lado de la otra */
        margin-left: 10px; /* Espacio entre las tablas */
        margin-right: 20px;
        border-spacing: 5px;
        border-collapse: collapse;
  }
    .cabecera-table{ 
        border:1px solid black; 
        text-align:center;
        background-color: #dbdbdb;
        font-size: 8px;
    }
    .lista-table{ 
        border:1px solid black; 
        text-align:center;
        font-size: 8px;
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
        margin: 3px;
        margin-top: 30px; /* Deja espacio para la cabecera */
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
        margin-top: -10px;
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
    /* .marca-agua {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 100%;
            text-align: center;
            font-size: 50%;
            opacity: 0.1;
            color: rgba(0, 0, 0, 0.8);
            transform: translate(-50%, -50%) ;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        } */
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
    .cuerpo{
        border:1px solid black;
    }
</style>
<body>

<header>
        <div  class="header-titulo" style="text-align: center;">
        
        </div>
        
        <div class="logo-izquierda">
            <img src="" style="height: 60px;">
        </div>
        <div class="logo-derecha">
            <img src="" style="height: 50px;" >
        </div>
        
        <div class="titulo"><u>REPORTE ACADEMICO</u> </div>  
        <div style="text-align:center;font-size:10px; margin: 5px;">IIEE : {{ $iiee }}</div>
        <div class="table-flotante-izquierda">
           
            <aside>
                <div>Nivel Educativo : </div>
                <div>Año : {{date('Y')}}</div>
                <div>Grado : {{$grado}}</div>
                <div>Seccion : {{$seccion}}</div>
            </aside>
            
        </div>    
        <div class="table-flotante-derecha">
            <aside>
                
                <div>Distrito : </div>
                <div>Provincia : </div> 
                <div>Departamento : </div>              
            </aside>
        </div>        
    </header>
    <main>
        
        <table class="cuerpo">
            <tr>
                <th class="cabecera-table"></th>
                <th class="cabecera-table"></th>
                <th colspan="{{count($cursos)}}" class="cabecera-table">ÁREA CURRICULAR</th>
            </tr>
            <tr>
                <th class="cabecera-table">Nro</th>
                <th class="cabecera-table">Nombres y Apellidos</th>
                @foreach ($cursos as $list)
                    <th class="cabecera-table">{{$list}}</th>
                @endforeach
            </tr>
            
            @foreach($alumnos as $list)
                <tr>
                <td>{{$loop->index+1}}</td>
                <td class="lista-table">{{$list["cEstNombres"]}} {{$list["cEstPaterno"]}} {{$list["cEstMaterno"]}}</td>
                @foreach ($cursos as $item)
                    <th class="cabecera-table">{{$list[$item]}}</th>
                @endforeach        
                </tr>
            @endforeach
        </table>
 
    </main>
    <hr>
    <main>
        
        <table class="cuerpo">
            <tr>
                <th class="cabecera-table"></th>
                <th class="cabecera-table"></th>
                <th colspan="{{count($cursos)}}" class="cabecera-table">ÁREAS POR ADECUAR (RVM 094-2020-MINEDU)</th>
                <th class="cabecera-table"></th>
            </tr>
            <tr>
                <th class="cabecera-table">Nro</th>
                <th class="cabecera-table">Nombres y Apellidos</th>
                @foreach ($cursos as $list)
                    <th class="cabecera-table">{{$list}}</th>
                @endforeach
                <th class="cabecera-table">Promedio</th>
            </tr>
            
            @foreach($alumnos as $list)
                <tr>
                <td>{{$loop->index+1}}</td>
                <td class="lista-table">{{$list["cEstNombres"]}} {{$list["cEstPaterno"]}} {{$list["cEstMaterno"]}}</td>
                @php
                $total=0;    
                @endphp
                @foreach ($cursos as $item)
                    
                    <th class="cabecera-table">{{($total+=(intval($list[$item]) * 3) / 8 + 2.5) ? null : null}} {{((intval($list[$item]) * 3) / 8 + 2.5) ?? '-'}}</th>
                @endforeach
                <td>{{$total}}</td>
                </tr>
            @endforeach
        </table>
        <p>Reporte de notas del estudainte emitido por la institucion Educativa: {{ $iiee }} Codigo Modular: {{$codigo}}</p>
        <p>Emitido: {{date("d \d\\e m \d\\e\l Y \H\o\\r\a: H:m:s")}}</p>
    </main>
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