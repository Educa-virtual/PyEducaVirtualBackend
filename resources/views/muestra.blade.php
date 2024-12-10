<!-- resources/views/pdf/muestra.blade.php -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Evaluación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            color: #333;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>{{ $evaluacion }}</h1>
    <p>{{ $descripcion }}</p>

    <h3>Preguntas:</h3>
    <ul>
        @foreach ($preguntas as $pregunta)
            <li>{{ $pregunta }}</li>
        @endforeach
    </ul>
</body>
</html>
