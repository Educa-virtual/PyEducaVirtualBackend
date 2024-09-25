<!DOCTYPE html>
<html>
<head>
    <title>Codigo de Verificacion</title>
</head>
<style>
    .cuadro_mensaje{
        padding:10px;
        border-radius: 2px;
    }
    .card{
        box-shadow: 9.8px 6.9px 10px rgba(0, 0, 0, 0.06), 78px 55px 80px rgba(0, 0, 0, 0.12);
        width: 600px;
        text-align:justify;
        margin:0 auto;
        background:white;
        border-radius: 8px;
        padding-bottom:20px; 
    }
    .imagen{
        text-align: center;
    }
    .titulo{
        text-align: center;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 24px;
        margin-bottom: 20px;
        background: rgb(254, 70, 70);
        padding: 16px;
        color: white
    }
    .cuerpo{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 18px;
        color: rgba(70, 70, 70, 1);
        padding: 16px;
    }
    .codigo{
        text-align: center;
        padding-bottom:10px; 
    }
    .resaltar{
        font-size:30px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        border-bottom: 5px solid rgb(254, 70, 70);
        font-weight: 600;
        color: rgba(70, 70, 70, 1);
        margin: 60px;
    }
    .nota{
        padding-left: 16px;
        padding-right: 16px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 14px;
        color: rgba(70, 70, 70, 1);
    }
    .correo{
        font-size:14px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-weight: 600;
        color: rgba(70, 70, 70, 1);
        margin: 60px;
    }
    .ingresar{
        background: rgb(254, 70, 70);
        padding: 10px;
        font-size:14px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        border: 0px;
        border-radius: 3px;
        color:white;
        cursor: pointer;
    }
</style>
<body>
    <div style="
        padding:10px;
        border-radius: 2px;">
        <div style="
        box-shadow: 9.8px 6.9px 10px rgba(0, 0, 0, 0.06), 78px 55px 80px rgba(0, 0, 0, 0.12);
        width: 600px;
        text-align:justify;
        margin:0 auto;
        background:white;
        border-radius: 8px;
        padding-bottom:20px;">
            <div style="
            text-align: center;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 24px;
            margin-bottom: 20px;
            background: rgb(254, 70, 70);
            padding: 16px;
            color: white">{{ $mailData['title'] }}
            </div>
            <div style="text-align: center;">
                <img src="{{ Storage::url('2024/logos/proteger.png') }}">
            </div>
            <p style="
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 18px;
            color: rgba(70, 70, 70, 1);
            padding: 16px;">
                Hola,
                se te ha enviado un código que deberás introducir el código de 6 dígitos en el sistema de verificación de 2 pasos.
            </p>
            <div style="
            text-align: center;
            padding-bottom:10px; ">
                <span style="
                font-size:30px;
                font-family: Verdana, Geneva, Tahoma, sans-serif;
                border-bottom: 5px solid rgb(254, 70, 70);
                font-weight: 600;
                color: rgba(70, 70, 70, 1);
                margin: 60px;">{{ $mailData['body'] }}</span>
            </div>
            <div style="
                text-align: center;
                padding-bottom:10px; ">
                <button
                style="
                background: rgb(254, 70, 70);
                padding: 10px;
                font-size:14px;
                font-family: Verdana, Geneva, Tahoma, sans-serif;
                border: 0px;
                border-radius: 3px;
                color:white;
                cursor: pointer;">Ingresar Código</button>
            </div>
            <p style="
            padding-left: 16px;
            padding-right: 16px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 14px;
            color: rgba(70, 70, 70, 1);">Si no ha solicitado esta acción, por favor ignore este mensaje.</p>
            <p style="
            padding-left: 16px;
            padding-right: 16px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 14px;
            color: rgba(70, 70, 70, 1);">Si quiere contactar con soporte técnico envie un mensaje al siguiente correo electrónico: <span class="correo">educacion_virtual@gremoquegua.edu.pe</span></p>
        </div>
        {{-- <h2>{{ $mailData['title'] }}</h3>
        <h1>{{ $mailData['body'] }}</h1> --}}
    </div>
    
</body>
</html>
