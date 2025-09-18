<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
</head>

<body style="font-family: Arial, sans-serif; background-color: #f6f6f6; margin: 0; padding: 0;">
    <div class="container"
        style="max-width: 600px; margin: 20px auto; background: #ffffff; padding: 20px; border-radius: 8px; border: 1px solid #e0e0e0;">
        <div class="content" style="margin: 20px 0; font-size: 15px; color: #333333; ">
            @yield('body')
        </div>

        <div class="footer" style="margin-top: 20px; font-size: 13px; color: #777777; text-align: center; border-top: 1px solid #e0e0e0; padding-top: 10px;">
            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="width: 100%">
                <tr>
                    <td width="50%" style="width: 50%; padding: 8px 0; background: #ffffff">
                        <img src="{{ $message->embed(public_path('images/logo-dremo.png')) }}" alt="Logo DREMO"
                            width="200"
                            style="display:block; border:0; outline:none; text-decoration:none; width:200px; height:auto; -ms-interpolation-mode:bicubic;" />
                    </td>
                    <td width="50%" style="width: 50%; padding: 8px 0; background: #ffffff">
                        <img src="{{ $message->embed(public_path('images/logo-plataforma-virtual.png')) }}"
                            alt="Logo EducaVirtual" width="60" align="right"
                            style="display:block; border:0; outline:none; text-decoration:none; width:60px; height:auto; -ms-interpolation-mode:bicubic;" />
                    </td>
                </tr>
            </table>

            <p style="margin-top:12px">Este es un correo automático, por favor no respondas a este mensaje.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
