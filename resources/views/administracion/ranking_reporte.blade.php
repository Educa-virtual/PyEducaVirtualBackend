<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Orden al Merito</title>
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
        text-transform: uppercase; /* Convierte todo el texto a mayúsculas */
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
        
        <div class="titulo">
            <u><span class="titulo">ORDEN DE MÉRITO: {{$order_merito_capturado ?? 'Sin datos'}}</span></u>
        </div>        
        <div class="table" style="text-align:center;font-size:13px; margin: 5px;">
                <div>LISTADO DE LOS PRIMEROS PUESTOS PARA TRANSPARENCIA</div>
                <div>Año Escolar : {{$year_capturado ?? 'Sin datos' }} </div>
                <div>Nombre de la IE : {{$documento_enviado ?? 'Sin datos' }}</div>
                <div>Codigo Modular : {{$codigo_modular ?? 'Sin datos' }} </div>
                <div>Grado : {{$grado_capturado ?? 'Sin datos' }}</div>
        </div>    
     
    </header>
    <main style="margin-top: 20px;">
        
        <table class="cuerpo">
            <tr>
                <th class="cabecera-table">DNI</th>
                <th class="cabecera-table">Codigo de Estudiante</th>
                <th class="cabecera-table">Apellidos y Nombres</th>
                <th class="cabecera-table">Promedio</th>
                <th class="cabecera-table">Puesto</th>
                <th class="Seccion">Puesto</th>
            </tr>
            @foreach($resultado_notas as $nota)
            <tr>
                <td>{{ $nota->dni }}</td>
                <td>{{ $nota->Codigo }}</td>
                <td>{{ $nota->Nombres }}</td>
                <td>{{ $nota->Promedio }}</td>
                <td>{{ $nota->Puesto }}</td>
                <td>{{ $nota->Seccion }}</td>
                
            </tr>
            @endforeach
        </table>
        <p>Ranking de Notas emitido por la institucion Educativa: {{$documento_enviado ?? 'Sin datos' }} Codigo Modular: {{$codigo_modular ?? 'Sin datos' }}  </p>
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