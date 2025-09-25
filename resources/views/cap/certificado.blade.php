<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Certificado de Capacitación</title>
  <style>
    @page {
      margin: 100px 40px 100px 40px;
      /* espacio para header y footer */
    }

    body {
      font-family: Arial, sans-serif;
    }

    /* Header y footer fijos */
    header {
      position: fixed;
      top: -80px;
      left: 0;
      right: 0;
      height: 80px;
    }

    footer {
      position: fixed;
      bottom: -120px;
      left: 0;
      right: 0;
      height: 80px;
    }

    /* Bandera hecha con tabla */
    .bandera {
      width: 100%;
      border-collapse: collapse;
      /* border-collapse: separate;
      border-spacing: 10px 0; */
    }

    .bandera td {
      height: 25px;
    }

    /* Colores */
    .azul {
      background: #0056b3;
    }

    .verde {
      background: #2e8b57;
    }

    .rojo {
      background: #c0392b;
    }

    /* Contenido */
    .contenido {
      text-align: center;

    }

    .logos {
      width: 100%;
      margin-bottom: 20px;
    }

    .logos td {
      width: 33%;
      text-align: center;
    }

    .logos img {
      max-width: 250px;
    }

    .titulo {
      font-size: 48px;
      font-weight: bold;
      color: #c0392b;
    }

    .subtitulo {
      font-size: 20px;
      margin-top: 5px;
    }

    .participante {
      font-size: 32px;
      font-weight: bold;
      color: #0056b3;
      margin: 0 0 15px 0;
    }

    .texto {
      font-size: 24px;
      margin: 10px 0;
    }

    .capacitacion {
      font-size: 28px;
      font-weight: bold;
      color: #0056b3;
      margin: 20px 0;
    }

    .texto-fecha {
      font-size: 24px;
      margin: 20px 0;
    }

    .fecha {
      font-size: 16px;
      margin: 20px 0;
    }

    .qr {
      margin-top: 30px;
    }

    .qr img {
      width: 100px;
      height: 100px;
    }
  </style>
</head>

<body>
  <!-- Header fijo -->
  <header>
    <table class="bandera">
      <tr>
        <td class="azul"></td>
        <td class="verde"></td>
        <td class="rojo"></td>
      </tr>
    </table>
  </header>

  <!-- Footer fijo -->
  <footer>
    <table class="bandera">
      <tr>
        <td class="azul"></td>
        <td class="verde"></td>
        <td class="rojo"></td>
      </tr>
    </table>
  </footer>

  <!-- Contenido -->
  <main>
    <div class="contenido">
      <table class="logos">
        <tr>
          <td><img src="{{ public_path('images/dremo_logo.png') }}"></td>
          <td>
            <div class="titulo">CERTIFICADO</div>
            <!-- <div class="subtitulo">Otorgado a:</div> -->
          </td>
          <td><img src="{{ public_path('images/nombre_dremo.png') }}"></td>
        </tr>
      </table>
      <div>
        <div class="subtitulo">Otorgado a:</div>
        <div class="participante">{{ $data->cNombres }}</div>
      </div>


      <div class="texto">
        Por haber participado y culminado satisfactoriamente la capacitación denominada:
      </div>

      <div class="capacitacion">
        {{ $data->cCapTitulo }}
      </div>

      <div class="texto-fecha">
        Realizado del {{ \Carbon\Carbon::parse($data->dFechaInicio)->translatedFormat('d \d\e F \d\e\l Y') }} al {{ \Carbon\Carbon::parse($data->dFechaFin)->translatedFormat('d \d\e F \d\e\l Y') }},
        con una duración de {{ $data->iTotalHrs }} horas académicas.
      </div>

      <div class="fecha" style="text-align: right; margin-top: 50px;">
        Moquegua, {{ \Carbon\Carbon::now()->translatedFormat('d \d\e F \d\e\l Y') }}
      </div>

      <!-- <div class="qr">
        
      </div> -->

    </div>
  </main>
</body>

</html>