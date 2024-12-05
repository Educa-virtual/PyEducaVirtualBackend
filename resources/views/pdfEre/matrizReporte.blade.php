<!-- resources/views/pdfEre/reporteMatriz.blade.php -->
<!DOCTYPE html>
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
</html>
