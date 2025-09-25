<?php

namespace App\Helpers;

class ImagenABase64
{
    public static function convertir($rutaImagen)
    {
        $tipoMime = mime_content_type($rutaImagen);
        $contenido = base64_encode(file_get_contents($rutaImagen));
        return "data:$tipoMime;base64,$contenido";
    }
}
