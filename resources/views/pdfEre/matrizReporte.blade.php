<!-- resources/views/pdfEre/reporteMatriz.blade.php -->
{{-- <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Matriz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table, .table th, .table td {
            border: 1px solid #000;
        }
        .table th, .table td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Reporte Matriz</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Curso</th>
                <th>Fecha</th>
                <th>Asistencia</th>
                <th>Docente</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($asistencias as $asistencia)
                <tr>
                    <td>{{ $asistencia->curso }}</td>
                    <td>{{ $asistencia->fecha }}</td>
                    <td>{{ $asistencia->asistencia }}</td>
                    <td>{{ $asistencia->docente }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> --}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Matriz de Evaluación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .header {
            font-size: 18px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="title">Matriz de Evaluación: {{ $evaluacion }}</div>
    <div class="header">Descripción: {{ $descripcion }}</div>

    <table>
        <thead>
            <tr>
                <th>Competencia</th>
                <th>Capacidad</th>
                <th>Desempeño</th>
                <th>Pregunta</th>
                <th>Clave</th>
            </tr>
        </thead>
        <tbody>
            @foreach($preguntas as $pregunta)
                <tr>
                    <td>{{ $pregunta->cCompetenciaNombre }}</td>
                    <td>{{ $pregunta->cCapacidadNombre }}</td>
                    <td>{{ $pregunta->cDesempenoDescripcion }}</td>
                    <td>{{ $pregunta->cPregunta }}</td>
                    <td>{{ $pregunta->cPreguntaClave }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

