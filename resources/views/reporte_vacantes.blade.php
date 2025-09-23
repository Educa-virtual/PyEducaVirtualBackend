<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de vacantes</title>
</head>


<style>
    @page {
            font-size: 1em;
    }
    body {
        /*margin: 3cm 2cm 2cm; vertical*/
        margin-top: 3.5cm;
        margin-left: 0.5cm;
        margin-right: 0.5cm;
       /* margin-bottom: 3.5cm; /* espacio para el pie */
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
        margin-top:30px;
        width: 45%; /* Ajusta el ancho de las tablas */
       /*  float: left; Permite que las tablas estén una al lado de la otra */
        margin-right: 10px; /* Espacio entre las tablas */
        border-spacing: 5px;
        border-collapse: collapse;
    }
    .table-flotante-derecha {
        border: 1px solid black; /* Bordes externos */
        margin-top:30px;
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
      
    
        </div>
    
        <div class="logo-derecha">
            @if($logoInsignia == 0)
                <img src="{{$logoVirtual}} " style="height: 50px;">
               
            @else
                <img src="{{ $logoInsignia }}" style="height: 70px;">
            @endif
        </div>
        <div class="logo-izquierda">
            <img src="{{ public_path('images/logo-dremo.png') }}" style="height: 50px;">
        </div>
        <br/><br/>
        <div class="titulo">RESUMEN DE VACANTES POR GRADO Y SECCIÓN <br/><br/></div>   
        <table>
        <tr>
                <th style="border:1px  solid black;margin:0;padding:5px; text-align:left;">Código Modular:</th>
                <td style="border:1px  solid black;margin:0;padding:5px;">{{$cIieeCodigoModular}} </td>
                <th style="border:1px  solid black;margin:0;padding:5px; text-align:left;">Institución Educativa:</th>
                <td style="border:1px  solid black;margin:0;padding:5px;">{{$cIieeNombre}} </td>
                <th style=" border:1px solid black;margin:0;padding:5px; text-align:left;">Fase:</th>
                <td style="border:1px  solid black;margin:0;padding:5px;">{{$cNivelNombre}} </td>
        </tr>

        <tr>
                <th style="border:1px  solid black;margin:0;padding:5px; text-align:left;">Tipo reporte:</th>
                <td style="border:1px  solid black;margin:0;padding:5px;">Vacantes </td>
                <th style=" border:1px solid black;margin:0;padding:5px; text-align:left;">Año:</th>
                <td style="border:1px  solid black;margin:0;padding:5px;">{{$cYAcadNombre}} </td>
                <th style=" border:1px solid black;margin:0;padding:5px; text-align:left;">Nivel / Programa:</th>
                <td style="border:1px  solid black;margin:0;padding:5px;">{{$cNivelTipoNombre}} </td>
        </tr>
        </table><br/><br/><br/><br/>
    </header>
        
    <footer>
    
    <!-- Copyright © p echo date("Y");?> -->
    </footer>
    <br/><br/><br/><br/>
    <main>
     
    <div class="marca-agua">
        <img src="{{$imageLogo}} ">
    </div>
    <br/>
         
    
    <div class="card">
        
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Nivel de grado</th>
                        <th>Sección</th>
                        <th>Vacante regular</th>
                        <th>Vacante NEE</th>
                        <th>Estado</th>
                        <th>Total Vacantes</th>

                        
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vacantes as $i => $fila)
                        <tr>
                            <td align='center'>{{ $i + 1 }}</td>
                            <td align='center'>{{ $fila['cGradoNombre'] }}</td>
                            <td align='center'>{{ $fila['cSeccionNombre'] }}</td>
                            <td align='center'>{{ $fila['iVacantesRegular'] }}</td>
                            <td align='center'>{{ $fila['iVacantesNEE'] }}</td>
                            <td align='center'>{{ $fila['cEstado'] }}</td>
                            <td align='center'> {{$fila['iVacantesRegular'] +  $fila['iVacantesNEE'] }}</td>

                                                       
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
   
    </main>
    
</body>


</html>