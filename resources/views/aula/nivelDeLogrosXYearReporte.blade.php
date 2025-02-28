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
        /*margin: 3cm 2cm 2cm; vertical*/
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
    }
    td{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 10px;
        margin: 0;
        text-align:center;
        border:1px 
        solid black;margin:0;
        padding:5px;
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
      /*  line-height: 15px;*/
      
       /* border: 1px solid blue;  Opcional: añade un borde para visualización */
    }
    main{
       /* position: relative;
        /*margin-top: 100px;  Deja espacio para la cabecera */
        /*margin-bottom: 5px;  Deja espacio para el pie de página */
        font-size: 12px;
       
        /*border: 2px solid green;  Opcional: añade un borde para visualizar mejor */

       
     
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
</style>
<body>

<header>
        <div  class="header-titulo" style="text-align: center;">
        "Año del Bicentenario, de la consolidación de nuestra Independencia, y de la conmemoración de las heroicas batallas de
        Junín y Ayacucho"
        </div>
        
        <div class="logo-izquierda">
            <img src="{{$logoInsignia}} " style="height: 60px;">
        </div>
        <div class="logo-derecha">
            <img src="{{$logoVirtual}} " style="height: 50px;" >
        </div>
        
        <div class="titulo"><u>REPORTE FINAL DE LOGROS DE APRENDIZAJE</u> </div>  
        <br>
        <div class="table-flotante-izquierda">
           
            <aside>
                <div>Cod-M : {{$headers['cod_Mod']}} / </div>
                <div>Estudiante : {{$estudiante['Estudiante']}} </div>
                <div>Tutor :  {{ $headers['docente'] }} </div>                
                <div>Año : {{ $headers['año'] }} </div>
            </aside>
            
        </div>    
        <div class="table-flotante-derecha">
            <aside>
                <div>Nivel Educativo: </div>
                <div>Sección - Turno : {{ $headers['Seccion_turno'] }} / </div>
                <div>Ciclo - Grado : {{ $headers['ciclo_grado'] }}</div>
                <div>Fase / Periodo / Nivel Educativo :  {{ $headers['nivel_educativo'] }} /</div>
            </aside>
        </div>        
    </header>
    <main>
    <div class="marca-agua">
        <img src="{{$imageLogo}} ">
    </div>
        
        <table border="1" class="cuerpo">
            <tr>
                <th rowspan="2" class="cabecera-table">Nro</th>
                <th rowspan="2" class="cabecera-table">Área Curricular</th>
                <th colspan="5" class="cabecera-table">AÑO 2024</th>                
            </tr>
            <tr>
                <th>TRIM I</th>
                <th>TRIM II</th>
                <th>TRIM III</th>    
                <th>Promedio Final</th>             
                <th>CONCLUSIÓN DESCRIPTIVA</th>
            </tr>
            <tbody>
                @php
                $item = 1; 
                @endphp
                @foreach($cursos as $curso) <!-- Cambié $respuesta['preguntas'] por $preguntas -->
                    <tr>
                        <td>{{ $item++ }}</td>
                        <td style="text-align:left;">{{ $curso['cCursoNombre'] }}</td>
                        <td>{{ $curso['iCalifIdPeriodo1'] ?? 'N/A'}}</td>
                        <td>{{ $curso['iCalifIdPeriodo2'] ?? 'N/A'}}</td>                        
                        <td>{{ $curso['iCalifIdPeriodo3'] ?? 'N/A'}}</td>
                        <td>{{ $curso['iPromedio'] ?? 'N/A'}}</td>
                        <td style="text-align:left;">{{$curso['cDetMatConclusionDescPromedio'] ?? 'N/A'}} </td>
            
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <div>
            CONCLUSIÓN DESCRIPTIVA FINAL
        </div>
        <table>
           <td border="1" style="text-align:left;">
                <div>- {{$estudiante['cMatrConclusionDescriptiva'] ?? 'N/A'}} </div>
           </td>
        </table>       
        <br><br>
        <table>
            <tr>
                <td style="border:0;">Legenda:</td>
                <td style="border:0;">[AD] Logro destacado</td>
                <td style="border:0;">[A] Logro esperado</td>
                <td style="border:0;">[B] En Proceso</td>
                <td style="border:0;">[C] En Inicio</td>
            </tr>
        </table>
    </main>    
    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Verdana, Geneva, Tahoma, sans-serif", "normal");
                $pdf->text(70, 570, "Página $PAGE_NUM de $PAGE_COUNT", $font, 10);
                $pdf->text(370, 570, "Autor:{{ $headers['docente'] }}", $font, 10);
                $pdf->text(670, 570, date("Y-m-d H:m:s"), $font, 10);
            ');
        }
    </script>
</body>
</html>