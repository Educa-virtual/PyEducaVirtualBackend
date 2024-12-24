<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Resumen de configuración para grados y secciones </title>
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
        <div  class="header-titulo" style="text-align: center;">
        "Año del Bicentenario, de la consolidación de nuestra Independencia, y de la conmemoración de las heroicas batallas de
        Junín y Ayacucho"
        </div>
    
        <div class="logo-izquierda">
            <img src="{{$logoInsignia}} " style="height: 60px;">
        </div>
        <div class="logo-derecha">
            <img src="{{$logoVirtual}} " style="height: 50px;">
        </div>
      
        <div class="titulo">RESUMEN DE CONFIGURACIÓN PARA GRADOS Y SECCIONES</div>    
        <table>
        <tr>
                <th style="border:1px  solid black;margin:0;padding:5px; text-align:left;">Codigo Modular:</th>
                <td style="border:1px  solid black;margin:0;padding:5px;">{{$cIieeCodigoModular}} </td>
                <th style="border:1px  solid black;margin:0;padding:5px; text-align:left;">Institucion Educativa:</th>
                <td style="border:1px  solid black;margin:0;padding:5px;">{{$cIieeNombre}} </td>
                <th style=" border:1px solid black;margin:0;padding:5px; text-align:left;">Fase:</th>
                <td style="border:1px  solid black;margin:0;padding:5px;">{{$cNivelNombre}} </td>
        </tr>

        <tr>
                <th style="border:1px  solid black;margin:0;padding:5px; text-align:left;">Educación Bilingüe:</th>
                <td style="border:1px  solid black;margin:0;padding:5px;">{{$bConfigEsBilingue}} </td>
                <th style=" border:1px solid black;margin:0;padding:5px; text-align:left;">Año:</th>
                <td style="border:1px  solid black;margin:0;padding:5px;">{{$cYAcadNombre}} </td>
                <th style=" border:1px solid black;margin:0;padding:5px; text-align:left;">Nivel / Programa:</th>
                <td style="border:1px  solid black;margin:0;padding:5px;">{{$cNivelTipoNombre}} </td>
        </tr>
        </table>
    </header>
  
    <main>
     
    <div class="marca-agua">
        <img src="{{$imageLogo}} ">
    </div>
    <div>
        <table class="table-flotante-derecha">
        <tr>
            <th colspan="4" style="border:1px  solid black;margin:0;padding:5px">Descripción de ambientes</th>
        </tr>
        <tr>
                <td>Educación Bilingüe:</td>
                <td >{{$bConfigEsBilingue}} </td>
                <td >Total de ambientes </td>
                <td >8</td>
        </tr>
        <tr>
                <td >Total de horas a asignar:</td>
                <td >{{$totalHoras}} </td>
                <td >Total de Docentes: </td>
                <td > 2</td>
        </tr>
        <tr>
            <td >Aula habilitadas</td>
            <td >{{$total_aulas}}</td>
            <td >Otros ambientes</td>
            <td >0</td>
        </tr>
        <tr>
            <td ">Total de horas no asignadas</td>
            <td >{{$totalHorasPendientes}} </td>
            <td ">Docentes no asignados </td>
            <td > 0</td>
        </tr>
    
        </table> 
        <table class="table-flotante-izquierda">
        <tr><th colspan="4" style="border:1px  solid black;margin:0;padding:5px"> Resumen de grados y secciones</th></tr>
            <tr>
                <td class="cabecera-table">N°</td>
                <td class="cabecera-table">Fase</td>
                <td class="cabecera-table"> Grado/edades</td>
                <td class="cabecera-table"> Secciones</td>
            
            </tr>

            @foreach ($secciones as $list)
    
            <tr>
            <td style="border: 1px solid black; padding: 5px; text-align:center">{{ $contador ++}}</td>
                    <td style="text-align:center;border:1px solid black;margin:0;padding:5px;">{{$cNivelNombre}}</th>
                
                    <td style="text-align:center;border:1px solid black;margin:0;padding:5px;">{{$list['grado']}} </td>
                    <td style="text-align:center;border:1px solid black;margin:0;padding:5px;">{{$list['parsedSecciones']}} </td>
                    
                </tr>
            @endforeach

        
        
        </table>
    </div><br/>
    <table>
    <tr><th colspan="9" class="cabecera-table"> Resumen de horas lectivas</th></tr>
    <tr>
        <th colspan="3" class="cabecera-table"> Detalle de área Curricular</th>
        <th rowspan="2" class="cabecera-table"> Total de secciones</th>
        <th rowspan="2" class="cabecera-table"> Horas acumuladas</th>
        <th colspan="3" class="cabecera-table"> Horas lectivas asignadas por modalidad</th>
        <th rowspan="2" class="cabecera-table"> Horas pendientes a asignar</th>
    </tr>
        <tr>
            <th class="cabecera-table" style="padding:5px;">N°</th>
            <th class="cabecera-table">Descripción</th>
            <th class="cabecera-table"> Hrs</th>
      
            <th class="cabecera-table"> Presencial</th>
            <th class="cabecera-table"> Semi-presencial</th>
            <th class="cabecera-table"> Distancia</th>
           
         
          
        </tr>
        <?php $contador = 1; ?>
        @foreach ($r_horas as $list)
  
        <tr>
            <td style="border: 1px solid black; padding: 5px; text-align:center">{{ $contador ++}}</td>
            <td style="text-align:center;border:1px solid black;margin:0;padding:5px; text-align:left">{{$list['cCursoNombre']}} </th>
            <td style="text-align:center;border:1px solid black;margin:0;padding:5px;">{{$list['nCursoTotalHoras']}} </td>
            <td style="text-align:center;border:1px solid black;margin:0;padding:5px;">{{$list['registrados']}} </td>
            <td style="text-align:center;border:1px solid black;margin:0;padding:5px;">{{$list['total_horas']}} </td>
            <td style="text-align:center;border:1px solid black;margin:0;padding:5px;">{{$list['suma_1']}} </td>
            <td style="text-align:center;border:1px solid black;margin:0;padding:5px;">{{$list['suma_2']}} </td>
            <td style="text-align:center;border:1px solid black;margin:0;padding:5px;">{{$list['suma_3']}} </td>
            <td style="text-align:center;border:1px solid black;margin:0;padding:5px;">
            <?php 
            $total_horas = isset($list['total_horas']) ? (float)$list['total_horas'] : 0;
            $suma_1 = isset($list['suma_1']) ? (float)$list['suma_1'] : 0;
            $suma_2 = isset($list['suma_2']) ? (float)$list['suma_2'] : 0;
            $suma_3 = isset($list['suma_3']) ? (float)$list['suma_3'] : 0;
            
            $resultado= $total_horas - $suma_1 - $suma_2 - $suma_3;
            ?>    
            {{ $resultado }} </td>
                
                
            </tr>
        @endforeach

      
    
    </table>
   
    </main>
    
    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Verdana, Geneva, Tahoma, sans-serif", "normal");
                $pdf->text(70, 570, "Página $PAGE_NUM de $PAGE_COUNT", $font, 10);
                $pdf->text(370, 570, "Autor:{{$cPersNombreLargo}}", $font, 10);
                $pdf->text(670, 570, date("Y-m-d H:m:s"), $font, 10);
            ');
        }
    </script>
</body>
</html>