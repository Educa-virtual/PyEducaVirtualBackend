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
    body{
        display: flex;
        flex-direction: column;
        margin-top: 1cm;
    }
    main{
        flex: 1;
        padding: 10px;
    }
    div{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }
    .table-header{
        width: 100%;
    }
    .table-header tr td{
        text-align:center;
        font-size:12px;
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
    .titulo{
        text-align:center;
        font-size:12px;
        font-weight:900;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }
    header{
        position: fixed;
        height: 2cm;
        top: 0;
        left: 0cm;
        right: 0cm;
        text-align: center;
        line-height: 30px;
    }
    footer{
        position: fixed;
        height: 2cm;
        bottom: 0;
        left: 0cm;
        right: 0cm;
        text-align: center;
        line-height: 30px;
    }
    .header img {
            height: 100%; /* La imagen ocupa toda la altura de la cabecera */
        }
</style>
<body>

    <header class="table-header">

    <div class="titulo">Resumen configuración para grados y secciones </div>
   
      
    </header>
    <main>
   
    <table>
    <tr>
            <th style="border:1px  solid black;margin:0;padding:5px;">EDUCACIÓN EDUCATIVA:</th>
            <td style="border:1px  solid black;margin:0;padding:5px;">{{$dre}} </td>
            <th style=" border:1px solid black;margin:0;padding:5px;">NIVEL DE PROGRAMA:</th>
            <td style="border:1px  solid black;margin:0;padding:5px;">{{$title}} </td>
    </tr>
    </table><br/>
    <table>

    <tr>
            <th style="border:1px  solid black;margin:0;padding:5px;">Educación Bilingüe:</th>
            <td style="border:1px  solid black;margin:0;padding:5px;">{{$bConfigEsBilingue}} </td>
            <th style="border:1px  solid black;margin:0;padding:5px;">Total de ambientes </th>
            <td style="border:1px  solid black;margin:0;padding:5px;">8</td>
            <th style=" border:1px solid black;margin:0;padding:5px;">Total de horas a asignar:</th>
            <td style="border:1px  solid black;margin:0;padding:5px;">{{$totalHoras}} </td>
            <th style=" border:1px solid black;margin:0;padding:5px;">Total de Docentes: </th>
            <td style="border:1px  solid black;margin:0;padding:5px;"> 2</td>
    </tr>
    <tr>
        <th style="border:1px  solid black;margin:0;padding:5px;">Aula habilitadas</th>
        <td style="border:1px  solid black;margin:0;padding:5px;">{{$total_aulas}}</td>
        <th style="border:1px  solid black;margin:0;padding:5px;">Otros ambientes</th>
        <td style="border:1px  solid black;margin:0;padding:5px;">0</td>
        <th style=" border:1px solid black;margin:0;padding:5px;">Total de horas no asignadas</th>
        <td style="border:1px  solid black;margin:0;padding:5px;">{{$totalHorasPendientes}} </td>
        <th style=" border:1px solid black;margin:0;padding:5px;">Docentes no asignados </th>
        <td style="border:1px  solid black;margin:0;padding:5px;"> 0</td>
    </tr>

          
    </table> <br/>
    <table>
    <tr><th colspan="4" style="border:1px  solid black;margin:0;padding:5px"> Resumen de grados y secciones</th></tr>
        <tr>
             <th style="border:1px solid black; margin:0; padding:5px; width:5%;">N°</th>
            <th style="border:1px solid black">Fase</th>
       
            <th style="border:1px solid black"> Grado/edades</th>
            <th style="border:1px solid black"> Secciones</th>
          
        </tr>

        @foreach ($secciones as $list)
  
        <tr>
        <td style="border: 1px solid black; padding: 5px;">{{ $contador ++}}</td>
                <td style="text-align:center;border:1px solid black;margin:0;padding:5px;">Regular </th>
             
                <td style="text-align:center;border:1px solid black;margin:0;padding:5px;">{{$list['grado']}} </td>
                <td style="text-align:center;border:1px solid black;margin:0;padding:5px;">{{$list['parsedSecciones']}} </td>
                
            </tr>
        @endforeach

      
    
    </table><br/>
    <table>
    <tr><th colspan="8" style="border:1px  solid black;margin:0;padding:5px"> Resumen de horas lectivas</th></tr>
        <tr>
            <th style="border:1px solid black;margin:0;padding:5px;">N°</th>
            <th style="border:1px solid black">Área curricular</th>
       
            <th style="border:1px solid black"> Hr lectiva</th>
            <th style="border:1px solid black"> Total hrs lectivas</th>
            <th style="border:1px solid black"> Hrs Asignadas presencial</th>
            <th style="border:1px solid black"> Hrs Asignadas semi-presencial</th>
            <th style="border:1px solid black"> Hrs Asignadas distancia</th>
            <th style="border:1px solid black"> Hrs pendientes a asignar</th>
         
          
        </tr>
        <?php $contador = 1; ?>
        @foreach ($r_horas as $list)
  
        <tr>
            <td style="border: 1px solid black; padding: 5px;">{{ $contador ++}}</td>
            <td style="text-align:center;border:1px solid black;margin:0;padding:5px;">{{$list['cCursoNombre']}} </th>
            <td style="text-align:center;border:1px solid black;margin:0;padding:5px;">{{$list['nCursoTotalHoras']}} </td>
            <td style="text-align:center;border:1px solid black;margin:0;padding:5px;">{{$list['total_horas']}} </td>
            <td style="text-align:center;border:1px solid black;margin:0;padding:5px;">{{$list['suma_1']}} </td>
            <td style="text-align:center;border:1px solid black;margin:0;padding:5px;">{{$list['suma_2']}} </td>
            <td style="text-align:center;border:1px solid black;margin:0;padding:5px;">{{$list['suma_3']}} </td>
            <td style="text-align:center;border:1px solid black;margin:0;padding:5px;">{{ $list['nCursoTotalHoras']-$list['suma_1']+$list['suma_2']+$list['suma_3']}} </td>
                
                
            </tr>
        @endforeach

      
    
    </table>
    
    </main>
    
    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Verdana, Geneva, Tahoma, sans-serif", "normal");
                $pdf->text(70, 570, "Página $PAGE_NUM de $PAGE_COUNT", $font, 10);
                $pdf->text(370, 570, "Autor:--", $font, 10);
                $pdf->text(670, 570, date("Y-m-d H:m:s"), $font, 10);
            ');
        }
    </script>
</body>
</html>