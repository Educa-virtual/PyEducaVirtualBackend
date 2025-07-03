<?php

namespace App\Services\Ere;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use WebPConvert\WebPConvert;

class ExtraerBase64
{
    /*
        Extrar archivos codificados en base64 de un texto y guardar en storage

        Input: texto con o sin archivos codificadas en base64
        Output: mismo texto, archivos codificados en base64 reemplazadas por url
    */

    private static function convertirImagenWebp($ruta, $uuid, $extension)
    {
        $carpetaPublic = Storage::disk('public')->path($ruta);
        $source = $carpetaPublic .  $uuid . '.' . $extension;
        $destination = $carpetaPublic .  $uuid . '.webp';
        WebPConvert::convert($source, $destination, []);
        unlink($source);
        return asset('storage/' . $ruta . $uuid . '.webp');
    }

    public static function extraer($texto, $id, $tipo)
    {
        $ruta = 'ere/preguntas/' . $tipo . '/' . $id . '/';

        if (strpos($texto, 'base64')) {
            $urls = [];
            preg_match_all('#data:image/[^;]+;base64,[A-Za-z0-9+/=]+#', $texto, $imagenesBase64);
            $imagenesBase64 = $imagenesBase64[0]; // Extraer solo los strings encontrados
            $contador = 0;

            foreach ($imagenesBase64 as $key => $imagenB64) {
                $contador++;

                $replace = substr($imagenB64, 0, strpos($imagenB64, ',') + 1);
                $imagen = str_replace($replace, '', $imagenB64);
                $imagen = str_replace(' ', '+', $imagen); // prevenir errores en decodificación
                $binario = base64_decode($imagen);

                // Detectar MIME real desde los bytes
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeReal = $finfo->buffer($binario);
                $extension = explode('/', $mimeReal)[1];

                $uuid = Uuid::uuid4();
                $nombreImagen = $uuid . '.' . $extension;
                $filePath = $ruta . $nombreImagen;

                Storage::disk('public')->put($filePath, $binario);

                // Convertir a WebP si es jpg, jpeg o png (basado en MIME real)
                if (in_array($mimeReal, ['image/jpeg', 'image/jpg', 'image/png'])) {
                    $urls[$key] = self::convertirImagenWebp($ruta, $uuid, $extension);
                } else {
                    // Para GIF u otros formatos, conservar la ruta original
                    $urls[$key] = asset('storage/' . $filePath);
                }
            }

            $texto = str_replace($imagenesBase64, $urls, $texto);
        }

        return $texto;
    }
}
